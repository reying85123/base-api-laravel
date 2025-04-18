<?php

namespace Modules\Language\Http\Controllers\Admin;

use Modules\Language\Services\LanguageContentFormService;

use Modules\Language\Models\LanguageContentForm;

use Modules\Language\Http\Requests\Admin\LanguageContentForm\CreateLanguageContentFormRequest;
use Modules\Language\Http\Requests\Admin\LanguageContentForm\UpdateLanguageContentFormRequest;

use Modules\Language\Http\Resources\Admin\LanguageContentForm\LanguageContentFormResource;
use Modules\Language\Http\Resources\Admin\LanguageContentForm\LanguageContentFormDetailResource;

use Jiannei\Response\Laravel\Support\Facades\Response;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LanguageContentFormController extends Controller
{
    protected $languageContentFormService;

    public function __construct(LanguageContentFormService $languageContentFormService)
    {
        $this->languageContentFormService = $languageContentFormService;
    }

    /**
     * 取得語系功能內容表單
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

        $languageContentForm = LanguageContentForm::query();

        if ($keyword = $request->input('keyword')) {
            $languageContentForm->whereKeyword(['name'], $keyword);
        }

        if ($orderby = $request->input('orderby')) {
            $languageContentForm->queryOrderBy($orderby);
        }

        $data = $request->filled(['page_size', 'page']) ? $this->paginate($request, $languageContentForm) : $languageContentForm->get();

        return Response::success(LanguageContentFormResource::collection($data));
    }

    /**
     * 新增語系功能內容表單
     *
     * @param  \Modules\Language\Http\Requests\Company\CreateLanguageContentFormRequest  $request
     * @return \Jiannei\Response\Laravel\Support\Facades\Response
     */
    public function store(CreateLanguageContentFormRequest $request)
    {
        DB::beginTransaction();

        try {
            $validatedData = $request->validated();

            $this->languageContentFormService->fill($validatedData);
            $this->languageContentFormService->save();

            $fields = $request->input('fields', []);
            $this->languageContentFormService->updateLanguageContentFormFields($fields);
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return Response::success(new LanguageContentFormDetailResource($this->languageContentFormService->getModel()->fresh()));
    }

    /**
     * 取得單一語系功能內容表單
     *
     * @param  int  $id
     * @return \Jiannei\Response\Laravel\Support\Facades\Response
     */
    public function show($id)
    {
        $languageContentForm = LanguageContentForm::findOrFail($id);
        return Response::success(new LanguageContentFormDetailResource($languageContentForm));
    }

    /**
     * 更新語系功能內容表單
     *
     * @param  \Modules\Language\Http\Requests\Company\UpdateLanguageContentFormRequest  $request
     * @param  int  $id
     * @return \Jiannei\Response\Laravel\Support\Facades\Response
     */
    public function update(UpdateLanguageContentFormRequest $request, $id)
    {
        $languageContentForm = LanguageContentForm::findOrFail($id);
        $this->languageContentFormService->setModel($languageContentForm);

        DB::beginTransaction();

        try {
            $validatedData = $request->validated();

            $this->languageContentFormService->fill($validatedData);
            $this->languageContentFormService->save();

            $fields = $request->input('fields', []);
            $this->languageContentFormService->updateLanguageContentFormFields($fields);
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return Response::success(new LanguageContentFormDetailResource($this->languageContentFormService->getModel()->fresh()));
    }

    /**
     * 刪除語系功能內容表單
     *
     * @param  int  $id
     * @return \Jiannei\Response\Laravel\Support\Facades\Response
     */
    public function destroy($id)
    {
        $languageContentForm = LanguageContentForm::findOrFail($id);
        $this->languageContentFormService->setModel($languageContentForm);

        DB::beginTransaction();

        try {
            $this->languageContentFormService->delete();
        } catch (\Exception $e) {
            DB::commit();

            throw $e;
        }

        DB::commit();

        return Response::noContent();
    }
}
