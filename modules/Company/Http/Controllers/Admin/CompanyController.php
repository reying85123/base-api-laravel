<?php

namespace Modules\Company\Http\Controllers\Admin;

use Modules\Company\Services\CompanyService;

use Modules\Company\Models\Company;

use Modules\Company\Http\Requests\Admin\CreateCompanyRequest;
use Modules\Company\Http\Requests\Admin\UpdateComapnyRequest;

use Modules\Company\Http\Resources\Admin\CompanyResource;

use Jiannei\Response\Laravel\Support\Facades\Response;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * 取得公司資訊列表
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
        ], [], [
            'keyword' => '關鍵字',
            'page_size' => '單頁筆數',
            'page' => '當前頁次',
            'orderby' => '排序',
        ]);

        $company = Company::query();

        if($keyword = $request->input('keyword')){
            $company->whereKeyword(['name'], $keyword);
        }

        if($orderby = $request->input('orderby')){
            $company->queryOrderBy($orderby);
        }

        if($request->filled(['page_size', 'page'])){
            $data = $this->paginate($request, $company);
        }else{
            $data = $company->get();
        }

        return Response::success(CompanyResource::collection($data));
    }

    /**
     * 新增公司資訊
     *
     * @param  \Modules\Company\Http\Requests\Admin\CreateCompanyRequest  $request
     * @param  CompanyService $companyService
     * @return \Jiannei\Response\Laravel\Support\Facades\Response
     */
    public function store(CreateCompanyRequest $request, CompanyService $companyService)
    {
        DB::beginTransaction();

        try {
            $companyService->fill($request->validated());

            $request->has('city') && ($companyService->associateCity($request->input('city.id')));
            $request->has('area') && ($companyService->associateArea($request->input('area.id')));

            $companyService->save();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return Response::success(new CompanyResource($companyService->getModel()->fresh()));
    }

    /**
     * 取得單一公司資訊
     *
     * @param  int  $id
     * @return \Jiannei\Response\Laravel\Support\Facades\Response
     */
    public function show($id)
    {
        return Response::success(new CompanyResource(Company::findOrFail($id)));
    }

    /**
     * 更新公司資訊
     *
     * @param  \Modules\Company\Http\Requests\Admin\UpdateComapnyRequest  $request
     * @param  int  $id
     * @param  CompanyService $companyService
     * @return \Jiannei\Response\Laravel\Support\Facades\Response
     */
    public function update(UpdateComapnyRequest $request, $id, CompanyService $companyService)
    {
        $companyService->setModel(Company::findOrFail($id));

        DB::beginTransaction();

        try {
            $companyService->fill($request->validated());

            $request->has('city') && ($companyService->associateCity($request->input('city.id')));
            $request->has('area') && ($companyService->associateArea($request->input('area.id')));

            $companyService->save();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return Response::success(new CompanyResource($companyService->getModel()->fresh()));
    }

    /**
     * 刪除公司資訊
     *
     * @param  int  $id
     * @param  CompanyService $companyService
     * @return \Jiannei\Response\Laravel\Support\Facades\Response
     */
    public function destroy($id, CompanyService $companyService)
    {
        $companyService->setModel(Company::findOrFail($id));

        DB::beginTransaction();

        try {
            $companyService->delete();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return Response::noContent();
    }
}
