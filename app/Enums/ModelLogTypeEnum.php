<?php

namespace App\Enums;

use Jiannei\Enum\Laravel\Enum;
use Jiannei\Enum\Laravel\Contracts\LocalizedEnumContract;

class ModelLogTypeEnum extends Enum implements LocalizedEnumContract
{
    const USER = 'user';
    const COMPANY = 'company';
}
