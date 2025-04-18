<?php

namespace App\Enums;

use Jiannei\Enum\Laravel\Enum;
use Jiannei\Enum\Laravel\Contracts\LocalizedEnumContract;

class MailLogStateEnum extends Enum implements LocalizedEnumContract
{
    const SUCCESS = 'success';
    const FAILED = 'failed';
}
