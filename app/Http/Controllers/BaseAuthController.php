<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;

class BaseAuthController extends Controller
{
    use AuthenticatesUsers;

    /**
     * 驗證門衛
     *
     * @var string
     */
    protected $guard;

    /**
     * 系統鎖定時間(分)
     *
     * @var int
     */
    protected $decayMinutes = 5;

    /**
     * 驗證錯誤次數
     *
     * @var int
     */
    protected $maxAttempts = 3;

    /**
     * Redirect the user after determining they are locked out
     *
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $failedTimes = $this->limiter()->attempts($this->throttleKey($request));
        $remainingTimes = $this->maxAttempts - $failedTimes;

        if($remainingTimes > 0){
            abort(401, trans('auth.failed_throttle', [
                'failed_times' => $failedTimes,
                'remaining_times' => $remainingTimes,
                'seconds' => $this->decayMinutes * 60,
                'minutes' => $this->decayMinutes,
            ]));
        }else{
            $this->sendLockoutResponse($request);
        }
    }

    /**
     * 取得驗證門衛
     */
    protected function guard()
    {
        return auth($this->guard);
    }
}
