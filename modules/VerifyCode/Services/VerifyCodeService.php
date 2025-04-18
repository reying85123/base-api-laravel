<?php

namespace Modules\VerifyCode\Services;

use Modules\VerifyCode\Models\VerifyCode;

use App\Abstracts\Services\AbstractModelService;

/**
 * @method VerifyCode getModel()
 * @property VerifyCode $model
 */
class VerifyCodeService extends AbstractModelService
{
    public function __construct(VerifyCode $model)
    {
        $this->setModel($model);
    }

    public static function generateVerifyCode($type, $jwtToken, $email = null, $phone = null, $account = null, $accountId = null)
    {
        $token = mt_rand(100000, 999999);

        $verifyCode = new VerifyCode([
            'token' => $token,
            'type' => $type,
            'account' => $account,
            'phone' => $phone,
            'email' => $email,
            'member_account_id' => $accountId,
            'jwt_token' => $jwtToken,
            'ip' => request()->getClientIp(),
        ]);

        $verifyCode->save();

        return $verifyCode;
    }

    public static function validateVerifyCode($code, $type, $email = null, $phone = null, $account = null, $accountId = null)
    {
        $verifyCode = VerifyCode::orderBy('id', 'desc')->firstWhere([
            'type' => $type,
            'email' => $email,
            'phone' => $phone,
            'account' => $account,
            'member_account_id' => $accountId,
        ]);
        return $verifyCode !== null && $verifyCode->token == $code;
    }

    public static function validateVerifyCodeExpired()
    {

        $verifyCode = VerifyCode::orderBy('id', 'desc')->firstWhere([]);

        return $verifyCode !== null && !$verifyCode->isExpired;
    }

    public static function deleteVerifyCode()
    {
        return VerifyCode::query()->where([])->delete();
    }
}
