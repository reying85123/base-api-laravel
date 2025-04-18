<?php

namespace Modules\Role\Http\Controllers\Admin;

use Modules\Role\Services\RoleService;

use Modules\Role\Models\Role;

use Modules\Role\Http\Requests\Admin\RoleAuth\UpdateRoleAuthRequest;

use Modules\Role\Http\Resources\Admin\RoleAuth\RoleAuthResource;

use Jiannei\Response\Laravel\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

class RoleAuthController extends Controller
{

    protected $roleService;

    public function __construct(
        RoleService $roleService
    ) {
        $this->roleService = $roleService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($roleId)
    {
        $role = Role::with('users')->findOrFail($roleId);

        return Response::success(new RoleAuthResource($role));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Modules\Role\Http\Requests\Admin\RoleAuth\UpdateRoleAuthRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRoleAuthRequest $request, $roleId)
    {
        $role = Role::findOrFail($roleId);

        DB::beginTransaction();

        try {
            if ($request->has('users')) {
                $userIds = $request->input('users.*.id');
                $role->users()->sync($userIds);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return Response::success(new RoleAuthResource($role->fresh()->load('users')));
    }
}
