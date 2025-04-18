<?php

namespace Modules\Language\Http\Controllers\Admin;

use Modules\Language\Services\LanguageSettingService;

use Modules\Language\Models\LanguageSetting;

use Modules\Language\Http\Requests\Admin\LanguageSetting\CreateLanguageSettingRequest;
use Modules\Language\Http\Requests\Admin\LanguageSetting\UpdateLanguageSettingRequest;

use Modules\Language\Http\Resources\Admin\LanguageSetting\LanguageSettingResource;

use Jiannei\Response\Laravel\Support\Facades\Response;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LanguageSettingController extends Controller
{
    protected $languageSettingService;

    public function __construct(LanguageSettingService $languageSettingService)
    {
        $this->languageSettingService = $languageSettingService;
    }

    /**
     * 取得語系設定
     *
     * @return \Jiannei\Response\Laravel\Support\Facades\Response
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'keyword' => 'sometimes|string|nullable',
            'page_size' => 'sometimes|integer',
            'page' => 'sometimes|integer',
            'orderby' => 'sometimes|string',
        ]);

        $languageSetting = LanguageSetting::query();

        if ($keyword = $request->input('keyword')) {
            $languageSetting->whereKeyword(['name'], $keyword);
        }

        if ($orderby = $request->input('orderby')) {
            $languageSetting->queryOrderBy($orderby);
        }

        $data = $request->filled(['page_size', 'page']) ? $this->paginate($request, $languageSetting) : $languageSetting->get();

        return Response::success(LanguageSettingResource::collection($data));
    }

    /**
     * 新增語系設定
     *
     * @param  \Modules\Language\Http\Requests\Company\CreateLanguageSettingRequest  $request
     * @return \Jiannei\Response\Laravel\Support\Facades\Response
     */
    public function store(CreateLanguageSettingRequest $request)
    {
        DB::beginTransaction();

        try {
            $this->languageSettingService->fill($request->validated());
            $this->languageSettingService->save();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return Response::success(new LanguageSettingResource($this->languageSettingService->getModel()->fresh()));
    }

    /**
     * 取得單一語系設定
     *
     * @param  int  $id
     * @return \Jiannei\Response\Laravel\Support\Facades\Response
     */
    public function show($id)
    {
        $languageSetting = LanguageSetting::findOrFail($id);
        return Response::success(new LanguageSettingResource($languageSetting));
    }

    /**
     * 更新語系設定
     *
     * @param  \Modules\Language\Http\Requests\Company\UpdateLanguageSettingRequest  $request
     * @param  int  $id
     * @return \Jiannei\Response\Laravel\Support\Facades\Response
     */
    public function update(UpdateLanguageSettingRequest $request, $id)
    {
        $languageSetting = LanguageSetting::findOrFail($id);
        $this->languageSettingService->setModel($languageSetting);

        DB::beginTransaction();

        try {
            $this->languageSettingService->fill($request->validated());
            $this->languageSettingService->save();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return Response::success(new LanguageSettingResource($this->languageSettingService->getModel()->fresh()));
    }

    /**
     * 刪除語系設定
     *
     * @param  int  $id
     * @return \Jiannei\Response\Laravel\Support\Facades\Response
     */
    public function destroy($id)
    {
        $languageSetting = LanguageSetting::findOrFail($id);
        $this->languageSettingService->setModel($languageSetting);

        DB::beginTransaction();

        try {
            $this->languageSettingService->delete();
        } catch (\Exception $e) {
            DB::commit();

            throw $e;
        }

        DB::commit();

        return Response::noContent();
    }
}
