<?php

namespace App\Enums;

use Jiannei\Enum\Laravel\Repositories\Enums\HttpStatusCodeEnum as BaseResponseCodeEnum;

class ResponseCodeEnum extends BaseResponseCodeEnum
{
    //通用錯誤
    const DATA_NOT_EXIST = 4049999;
    const UNAUTHORIZED_ACCESS = 4010000; // 未授權訪問
    const API_PARAMS_ERROR = 4009999;
}
