<?php

namespace Modules\FrontendMenu\Enums;

use Jiannei\Enum\Laravel\Enum;
use Jiannei\Enum\Laravel\Contracts\LocalizedEnumContract;

class FrontendMenuTypeEnum extends Enum implements LocalizedEnumContract
{
    const DROPDOWN = 'dropdown';
    const LINK = 'link';
    const INTERNAL_LINK = 'internal_link';
}
