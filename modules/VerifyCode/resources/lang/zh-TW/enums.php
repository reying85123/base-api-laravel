<?php

use Modules\VerifyCode\Enums\VerifyCodeTypeEnum;

return [
    'VerifyCodeTypeEnum' => [
        VerifyCodeTypeEnum::REGISTER => '註冊',
        VerifyCodeTypeEnum::LOGIN => '登入',
        VerifyCodeTypeEnum::FORGET_PASSWORD => '忘記密碼',
        VerifyCodeTypeEnum::BIND => '綁定',
        VerifyCodeTypeEnum::PHONE => '手機',
        VerifyCodeTypeEnum::EMAIL => '電子信箱',
    ],
];
