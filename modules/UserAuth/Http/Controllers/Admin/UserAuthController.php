<?php

namespace Modules\UserAuth\Http\Controllers\Admin;

use Modules\User\Services\UserService;
use Modules\UserAuth\Services\UserAuthService;

use Modules\UserAuth\Http\Requests\Admin\UpdateProfileRequest;
use Modules\UserAuth\Http\Requests\Admin\ChangeMyPasswordRequest;

use Modules\UserAuth\Http\Resources\Admin\ProfileResource;
use Modules\UserAuth\Http\Resources\Admin\TokenResource;
use Modules\UserAuth\Http\Resources\Admin\PermissionResource;

use App\Http\Controllers\BaseAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Jiannei\Response\Laravel\Support\Facades\Response;

/**
 * @method \PHPOpenSourceSaver\JWTAuth\JWTGuard guard()
 */
class UserAuthController extends BaseAuthController
{
    protected $guard = 'api';

    /**
     * 人員登入
     *
     * @param Request $request
     */
    public function login(Request $request)
    {
        return parent::login($request);
    }

    protected function sendLoginResponse(Request $request)
    {
        $jwtToken = UserAuthService::attempt($request->only(['account', 'password']));

        $payload = UserAuthService::getPayLoad();

        $this->clearLoginAttempts($request);

        $user = UserAuthService::toUser();

        if(!$user->is_enable){
            //如果帳號不啟用，回傳錯誤
            $this->sendDisableResponse($request);
        }

        return Response::success(new TokenResource($payload, $jwtToken));
    }

    protected function sendDisableResponse(Request $request)
    {
        abort(403, trans('auth.disable'));
    }

    public function username()
    {
        return 'account';
    }

    /**
     * 取得我的(當前登入者)資訊
     */
    public function getProfile()
    {
        return Response::success(new ProfileResource(UserAuthService::toUser()));
    }

    /**
     * 更新我的(當前登入者)資料
     *
     * @param UpdateProfileRequest $request
     * @param UserService $userService
     */
    public function updateProfile(UpdateProfileRequest $request, UserService $userService)
    {
        $userService->setModel(UserAuthService::toUser());

        DB::beginTransaction();

        try {
            $userService
                ->fill($request->validated())
                ->save();
        } catch (\Exception $e) {
            DB::rollback();

            throw $e;
        }

        DB::commit();

        return Response::success(new ProfileResource($userService->getModel()->fresh()));
    }

    /**
     * 取得登入者權限
     */
    public function permission()
    {
        $permissions = UserAuthService::toUser()->getAllPermissions();

        return Response::success(PermissionResource::collection($permissions));
    }

    /**
     * 刷新登入令牌Token
     *
     * @param Request $request
     */
    public function refreshToken(Request $request)
    {
        $this->validate($request, [
            'token' => 'required|string',
        ], [], [
            'token' => '登入令牌Token',
        ]);

        $guard = UserAuthService::guard()->setToken($request->input('token'));

        $token = $guard->refresh();
        $payload = $guard->setToken($token)->payload();

        return Response::success(new TokenResource($payload, $token));
    }

    /**
     * 修改我的(當前登入者)密碼
     * 
     * @param ChangeMyPasswordRequest $request
     * @param UserService $userService
     */
    public function changeMyPassword(ChangeMyPasswordRequest $request, UserService $userService)
    {
        $userService->setModel(UserAuthService::toUser());

        $oldPasswordValidate = auth()->validate([
            'account' => $userService->getModel()->account,
            'password' => $request->get('old_password'),
        ]);

        abort_if(!$oldPasswordValidate, 400, '舊密碼錯誤');

        abort_if($request->get('old_password') === $request->get('new_password'), 400, '新密碼不可與當前密碼一致');

        DB::beginTransaction();

        try {
            $userService
                ->setPassword($request->get('new_password'))
                ->save();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        return Response::success(null);
    }
}
