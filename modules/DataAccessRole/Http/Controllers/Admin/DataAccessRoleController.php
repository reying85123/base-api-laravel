<?php

namespace Modules\DataAccessRole\Http\Controllers\Admin;

use Modules\DataAccessRole\Models\DataAccessRole;

use Modules\DataAccessRole\Services\DataAccessRoleService;

use Modules\DataAccessRole\Http\Requests\Admin\CreateDataAccessRoleRequest;
use Modules\DataAccessRole\Http\Requests\Admin\UpdateDataAccessRoleRequest;

use Modules\DataAccessRole\Http\Resources\Admin\DataAccessRoleResource;
use Modules\DataAccessRole\Http\Resources\Admin\DataAccessRoleDetailResource;

use Jiannei\Response\Laravel\Support\Facades\Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class DataAccessRoleController extends Controller
{

    protected $dataAccessRoleService;

    public function __construct(
        DataAccessRoleService $dataAccessRoleService
    ) {
        $this->dataAccessRoleService = $dataAccessRoleService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'keyword' => 'sometimes',
            'page_size' => 'sometimes|integer',
            'page' => 'sometimes|integer',
            'orderby' => 'sometimes|string',
        ]);

        $dataAccessRole = DataAccessRole::query();

        if ($keyword = $request->input('keyword')) {
            $dataAccessRole->whereKeyword(['name'], $keyword);
        }

        if ($orderby = $request->input('orderby')) {
            $dataAccessRole->queryOrderBy($orderby);
        }

        if ($request->filled(['page_size', 'page'])) {
            $data = $this->paginate($request, $dataAccessRole);
        } else {
            $data = $dataAccessRole->get();
        }

        return Response::success(DataAccessRoleResource::collection($data));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateDataAccessRoleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateDataAccessRoleRequest $request)
    {
        DB::beginTransaction();

        try {
            $this->dataAccessRoleService
                ->fill($request->validated())
                ->save();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return Response::success(new DataAccessRoleDetailResource($this->dataAccessRoleService->getModel()->fresh()));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dataAccessRole = DataAccessRole::query()->findOrFail($id);

        return Response::success(new DataAccessRoleDetailResource($dataAccessRole));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateDataAccessRoleRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDataAccessRoleRequest $request, $id)
    {
        $dataAccessRole = DataAccessRole::query()->findOrFail($id);

        $this->dataAccessRoleService->setModel($dataAccessRole);

        DB::beginTransaction();

        try {
            $this->dataAccessRoleService
                ->fill($request->validated())
                ->save();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return Response::success(new DataAccessRoleDetailResource($this->dataAccessRoleService->getModel()->fresh()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dataAccessRole = DataAccessRole::query()->findOrFail($id);

        $this->dataAccessRoleService->setModel($dataAccessRole);

        if ($dataAccessRole->users()->count() > 0) {
            abort(409, '已被人員設定，無法刪除');
        }

        DB::beginTransaction();

        try {
            $this->dataAccessRoleService->delete();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return Response::noContent();
    }
}
