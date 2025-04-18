<?php

namespace Modules\User\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Modules\User\Services\UserService;

use Modules\User\Models\User;
use Modules\Role\Models\Role;

use Modules\User\Http\Requests\Admin\CreateUserRequest;
use Modules\User\Http\Requests\Admin\UpdateUserRequest;

use Modules\User\Http\Resources\Admin\UserResource;

use Jiannei\Response\Laravel\Support\Facades\Response;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Jiannei\Response\Laravel\Support\Facades\Response
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'keyword' => 'sometimes|string|nullable',
            'page_size' => 'sometimes|integer',
            'page' => 'sometimes|integer',
            'orderby' => 'sometimes|string',
            'role_id' => 'sometimes|integer',
        ]);

        $user = User::query()->with(['roles']);

        if ($keyword = $request->input('keyword')) {
            $user
                ->where(function ($query) use ($keyword) {
                    $query
                        ->whereKeyword(['name', 'email', 'phone', 'account'], $keyword)
                        ->orWhereHas('roles', function ($query) use ($keyword) {
                            $query->whereKeyword(['name'], $keyword);
                        })
                        ->orWhereHas('company', function ($query) use ($keyword) {
                            $query->whereKeyword(['name'], $keyword);
                        });
                });
        }

        if ($request->has('role_id')) {
            $user->whereHas('roles', function ($query) use ($request) {
                $query->whereId($request->query('role_id'));
            });
        }

        if ($orderby = $request->input('orderby')) {
            $user->queryOrderBy($orderby);
        }

        if ($request->filled(['page_size', 'page'])) {
            $data = $this->paginate($request, $user);
        } else {
            $data = $user->get();
        }

        return Response::success(UserResource::collection($data));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Modules\User\Http\Requests\Admin\User\CreateUserRequest  $request
     * @param  UserService $userService
     * @return \Jiannei\Response\Laravel\Support\Facades\Response
     */
    public function store(CreateUserRequest $request, UserService $userService)
    {
        DB::beginTransaction();

        try {

            $userService
                ->fill($request->validated())
                ->setAccount($request->get('account'))
                ->setPassword($request->get('password'));
            $userService->getModel()->syncRoles(Role::where('id', $request->input('role.id'))->get()->pluck('name'));
            $request->has('company') && ($userService->associateCompany($request->input('company.id')));
            $request->has('company_job') && ($userService->associateCompanyJob($request->input('company_job.id')));
            $userService->save();

            ($request->has('data_access_roles') && ($dataAccessRoles = $request->input('data_access_roles.*.id')) !== null) && $userService->syncDataAccessRoles($dataAccessRoles);
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return Response::success(new UserResource($userService->getModel()->fresh(['roles'])));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Jiannei\Response\Laravel\Support\Facades\Response
     */
    public function show($id)
    {
        return Response::success(new UserResource(User::findOrFail($id)));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Modules\User\Http\Requests\User\UpdateUserRequest  $request
     * @param  int  $id
     * @param  UserService $userService
     * @return \Jiannei\Response\Laravel\Support\Facades\Response
     */
    public function update(UpdateUserRequest $request, $id, UserService $userService)
    {
        $userService->setModel(User::findOrFail($id));

        DB::beginTransaction();

        try {

            $userService->fill($request->validated());
            $userService->getModel()->syncRoles(Role::where('id', $request->input('role.id'))->get());
            $request->has('company') && ($userService->associateCompany($request->input('company.id')));
            $request->has('company_job') && ($userService->associateCompanyJob($request->input('company_job.id')));
            $userService->save();

            ($request->has('data_access_roles') && ($dataAccessRoles = $request->input('data_access_roles.*.id')) !== null) && $userService->syncDataAccessRoles($dataAccessRoles);
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return Response::success(new UserResource($userService->getModel()->fresh(['roles'])));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @param  UserService $userService
     * @return \Jiannei\Response\Laravel\Support\Facades\Response
     */
    public function destroy($id, UserService $userService)
    {
        $userService->setModel(User::findOrFail($id));

        DB::beginTransaction();

        try {
            $userService->delete();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return Response::noContent();
    }

    /**
     * 重置使用者密碼
     *
     * @param int $id
     * @param UserService $userService
     * @return void
     */
    public function resetPassword($id, UserService $userService)
    {
        $userService->setModel(User::findOrFail($id));

        DB::beginTransaction();

        try {
            $resetPassword = Str::random(8);

            $userService->setPassword($resetPassword);
            $userService->save();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return Response::success([
            'password' => $resetPassword,
        ]);
    }
}
