<?php

namespace Modules\CompanyJob\Http\Controllers\Admin;

use Modules\CompanyJob\Services\CompanyJob\CompanyJobService;

use Modules\CompanyJob\Models\CompanyJob;

use Modules\CompanyJob\Http\Requests\Admin\CreateCompanyJobRequest;
use Modules\CompanyJob\Http\Requests\Admin\UpdateCompanyJobRequest;

use Modules\CompanyJob\Http\Resources\Admin\CompanyJobResource;

use Jiannei\Response\Laravel\Support\Facades\Response;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyJobController extends Controller
{
    /**
     * 取得公司職稱
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

        $companyJob = CompanyJob::query();

        if($keyword = $request->input('keyword')){
            $companyJob->whereKeyword(['name'], $keyword);
        }

        if($orderby = $request->input('orderby')){
            $companyJob->queryOrderBy($orderby);
        }

        if($request->filled(['page_size', 'page'])){
            $data = $this->paginate($request, $companyJob);
        }else{
            $data = $companyJob->get();
        }

        return Response::success(CompanyJobResource::collection($data));
    }

    /**
     * 新增公司職稱
     *
     * @param  \Modules\CompanyJob\Http\Requests\Company\CreateCompanyJobRequest  $request
     * @param  CompanyJobService $companyJobService
     * @return \Jiannei\Response\Laravel\Support\Facades\Response
     */
    public function store(CreateCompanyJobRequest $request, CompanyJobService $companyJobService)
    {
        DB::beginTransaction();

        try {
            $companyJobService->fill($request->validated());

            if($parentJobId = $request->input('parent_job.id')){
                $companyJobService->associateCompanyJob(CompanyJob::findOrFail($parentJobId));
            }

            $companyJobService->save();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return Response::success(new CompanyJobResource($companyJobService->getModel()->fresh()));
    }

    /**
     * 取得單一公司職稱
     *
     * @param  int  $id
     * @return \Jiannei\Response\Laravel\Support\Facades\Response
     */
    public function show($id)
    {
        return Response::success(new CompanyJobResource(CompanyJob::findOrFail($id)));
    }

    /**
     * 更新公司職稱
     *
     * @param  \Modules\CompanyJob\Http\Requests\Company\UpdateCompanyJobRequest  $request
     * @param  int  $id
     * @param  CompanyJobService $companyJobService
     * @return \Jiannei\Response\Laravel\Support\Facades\Response
     */
    public function update(UpdateCompanyJobRequest $request, $id, CompanyJobService $companyJobService)
    {
        $companyJobService->setModel(CompanyJob::findOrFail($id));

        DB::beginTransaction();

        try {
            $companyJobService->fill($request->validated());

            if($request->has('parent_job')){
                $parentJobId = $request->input('parent_job.id');

                $companyJobService->associateCompanyJob(
                    $parentJobId !== null ? CompanyJob::findOrFail($parentJobId): null
                );
            }

            $companyJobService->save();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return Response::success(new CompanyJobResource($companyJobService->getModel()->fresh()));
    }

    /**
     * 刪除公司職稱
     *
     * @param  int  $id
     * @param  CompanyJobService $companyJobService
     * @return \Jiannei\Response\Laravel\Support\Facades\Response
     */
    public function destroy($id, CompanyJobService $companyJobService)
    {
        $companyJobService->setModel(CompanyJob::findOrFail($id));

        DB::beginTransaction();

        try {
            $companyJobService->delete();

            //將該分類之子分類全部解綁
            CompanyJobService::unbindChildCompanyJob($companyJobService->getModel());
        } catch (\Exception $e) {
            DB::commit();

            throw $e;
        }

        DB::commit();

        return Response::noContent();
    }
}
