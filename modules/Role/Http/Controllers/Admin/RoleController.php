<?php

namespace Modules\Role\Http\Controllers\Admin;

use Modules\Role\Services\RoleService;

use Modules\Role\Models\Role;
use Spatie\Permission\Models\Permission;

use Modules\Role\Http\Requests\Admin\Role\CreateRoleRequest;
use Modules\Role\Http\Requests\Admin\Role\UpdateRoleRequest;

use Modules\Role\Http\Resources\Admin\Role\RoleResource;

use Jiannei\Response\Laravel\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'keyword' => 'sometimes|string|nullable',
            'page_size' => 'sometimes|integer',
            'page' => 'sometimes|integer',
            'orderby' => 'sometimes|string',
        ]);

        $role = Role::query()->with(['permissions']);

        if ($request->has('keyword') && ($keyword = $request->get('keyword')) !== null) {
            $role->whereKeyword(['name'], $keyword);
        }

        if ($request->has('guard_name') && ($guardName = $request->get('guard_name')) !== null) {
            $role->whereGuardName($guardName);
        }

        if ($request->has('orderby') && ($orderBy = $request->get('orderby')) !== null) {
            $role->queryOrderBy($orderBy);
        }
        $data = $request->filled(['page_size', 'page']) ? $role->paginate(intval($request->get('page_size')), ['*'], 'page', intval($request->get('page'))) : $role->get();

        return Response::success(RoleResource::collection($data));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  RoleService $roleService
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRoleRequest $request, RoleService $roleService)
    {
        DB::beginTransaction();

        try {
            $roleService->fill($request->validated());
            $roleService->save();

            $permissions = Permission::whereIn('id', $request->input('permissions.*.id'))->get();
            $roleService->getModel()->syncPermissions($permissions);
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return Response::success(new RoleResource($roleService->getModel()->fresh(['permissions'])));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Response::success(new RoleResource(Role::findOrFail($id)));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @param  RoleService $roleService
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRoleRequest $request, $id, RoleService $roleService)
    {
        $roleService->setModel(Role::findOrFail($id));

        DB::beginTransaction();

        try {
            $roleService->fill($request->validated())->save();
            $permissions = Permission::whereIn('id', $request->input('permissions.*.id'))->get();
            $roleService->getModel()->syncPermissions($permissions);

            $roleService->touch();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return Response::success(new RoleResource($roleService->getModel()->fresh(['permissions'])));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @param  RoleService $roleService
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, RoleService $roleService)
    {
        $roleService->setModel(Role::findOrFail($id));

        abort_if($roleService->getModel()->users->count() > 0, 400, '此群組尚有人員正在使用，無法刪除');

        DB::beginTransaction();

        try {
            $roleService->delete();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return Response::noContent();
    }
}
