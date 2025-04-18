<?php

namespace Modules\Language\Http\Controllers\Admin;

use Modules\Language\Services\LanguageDataService;

use Modules\Language\Models\LanguageData;

use Modules\Language\Http\Requests\Admin\LanguageData\CreateLanguageDataRequest;
use Modules\Language\Http\Requests\Admin\LanguageData\UpdateLanguageDataRequest;

use Modules\Language\Http\Resources\Admin\LanguageData\LanguageDataResource;

use Jiannei\Response\Laravel\Support\Facades\Response;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LanguageDataController extends Controller
{
    protected $languageDataService;

    public function __construct(LanguageDataService $languageDataService)
    {
        $this->languageDataService = $languageDataService;
    }

    /**
     * 取得語系資料
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

        $languageData = LanguageData::query();

        if ($keyword = $request->input('keyword')) {
            $languageData->whereKeyword(['name'], $keyword);
        }

        if ($orderby = $request->input('orderby')) {
            $languageData->queryOrderBy($orderby);
        }

        $data = $request->filled(['page_size', 'page']) ? $this->paginate($request, $languageData) : $languageData->get();

        return Response::success(LanguageDataResource::collection($data));
    }

    /**
     * 新增語系資料
     *
     * @param  \Modules\Language\Http\Requests\Company\CreateLanguageDataRequest  $request
     * @return \Jiannei\Response\Laravel\Support\Facades\Response
     */
    public function store(CreateLanguageDataRequest $request)
    {
        DB::beginTransaction();

        try {
            $this->languageDataService->fill($request->validated());
            $this->languageDataService->save();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return Response::success(new LanguageDataResource($this->languageDataService->getModel()->fresh()));
    }

    /**
     * 取得單一語系資料
     *
     * @param  int  $id
     * @return \Jiannei\Response\Laravel\Support\Facades\Response
     */
    public function show($id)
    {
        $languageData = LanguageData::findOrFail($id);
        return Response::success(new LanguageDataResource($languageData));
    }

    /**
     * 更新語系資料
     *
     * @param  \Modules\Language\Http\Requests\Company\UpdateLanguageDataRequest  $request
     * @param  int  $id
     * @return \Jiannei\Response\Laravel\Support\Facades\Response
     */
    public function update(UpdateLanguageDataRequest $request, $id)
    {
        $languageData = LanguageData::findOrFail($id);
        $this->languageDataService->setModel($languageData);

        DB::beginTransaction();

        try {
            $this->languageDataService->fill($request->validated());
            $this->languageDataService->save();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return Response::success(new LanguageDataResource($this->languageDataService->getModel()->fresh()));
    }

    /**
     * 刪除語系資料
     *
     * @param  int  $id
     * @return \Jiannei\Response\Laravel\Support\Facades\Response
     */
    public function destroy($id)
    {
        $languageData = LanguageData::findOrFail($id);
        $this->languageDataService->setModel($languageData);

        DB::beginTransaction();

        try {
            $this->languageDataService->delete();
        } catch (\Exception $e) {
            DB::commit();

            throw $e;
        }

        DB::commit();

        return Response::noContent();
    }
}
