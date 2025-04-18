<?php

namespace Modules\VerifyCode\Enums;

use Jiannei\Enum\Laravel\Enum;
use Jiannei\Enum\Laravel\Contracts\LocalizedEnumContract;

class VerifyCodeTypeEnum extends Enum implements LocalizedEnumContract
{
    const REGISTER = 'register';
    const LOGIN = 'login';
    const FORGET_PASSWORD = 'forget_password';
    const BIND = 'bind';
    const PHONE = 'phone';
    const EMAIL = 'email';

    public static function toSelectArray(): array
    {
        return [
            self::REGISTER => trans("VerifyCode::enums.VerifyCodeTypeEnum." . self::REGISTER),
            self::LOGIN => trans("VerifyCode::enums.VerifyCodeTypeEnum." . self::LOGIN),
            self::FORGET_PASSWORD => trans("VerifyCode::enums.VerifyCodeTypeEnum." . self::FORGET_PASSWORD),
            self::BIND => trans("VerifyCode::enums.VerifyCodeTypeEnum." . self::BIND),
            self::PHONE => trans("VerifyCode::enums.VerifyCodeTypeEnum." . self::PHONE),
            self::EMAIL => trans("VerifyCode::enums.VerifyCodeTypeEnum." . self::EMAIL),
        ];
    }
}
