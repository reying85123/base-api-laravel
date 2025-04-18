<?php

namespace App\Services\ThirdParty\Newebpay\Repositories\Enums;

use Jiannei\Enum\Laravel\Enum;
use Jiannei\Enum\Laravel\Contracts\LocalizedEnumContract;

class PaymentTypeEnum extends Enum implements LocalizedEnumContract
{
    const CREDIT_SINGLE = 'CREDIT';         //信用卡-單次付清
    const CREDIT_INSTALLMENT = 'InstFlag';    //信用卡-分期付款
    const ATM = 'VACC';                      //虛擬ATM
}