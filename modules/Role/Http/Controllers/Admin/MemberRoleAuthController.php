<?php

namespace Modules\Role\Http\Controllers\Admin;

use Modules\Role\Services\RoleService;

use Modules\Role\Models\MemberRole;

use Modules\Role\Http\Requests\Admin\MemberRoleAuth\UpdateMemberRoleAuthRequest;

use Modules\Role\Http\Resources\Admin\MemberRoleAuth\MemberRoleAuthResource;

use Jiannei\Response\Laravel\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

class MemberRoleAuthController extends Controller
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
        $role = MemberRole::with('members')->findOrFail($roleId);

        return Response::success(new MemberRoleAuthResource($role));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Modules\Role\Http\Requests\Admin\RoleAuth\UpdateRoleAuthRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMemberRoleAuthRequest $request, $roleId)
    {
        $role = MemberRole::findOrFail($roleId);

        DB::beginTransaction();

        try {
            if ($request->has('members')) {
                $memberIds = $request->input('members.*.id');
                $role->members()->sync($memberIds);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return Response::success(new MemberRoleAuthResource($role->fresh()->load('members')));
    }
}
