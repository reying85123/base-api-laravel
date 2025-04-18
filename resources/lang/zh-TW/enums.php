<?php

use App\Enums\ResponseCodeEnum;
use App\Enums\MediaTypeEnum;
use App\Enums\PermissionExtraNameEnum;

return [
    ResponseCodeEnum::class => [
        //系統訊息
        ResponseCodeEnum::HTTP_OK => '操作成功',    //200
        ResponseCodeEnum::HTTP_BAD_REQUEST => '請求錯誤',   //400
        ResponseCodeEnum::HTTP_NOT_FOUND => '找不到項目',   //404
        ResponseCodeEnum::HTTP_FORBIDDEN => '權限不足，無法操作',   //403
        ResponseCodeEnum::HTTP_UNPROCESSABLE_ENTITY => 'API參數錯誤',   //422
        ResponseCodeEnum::HTTP_INTERNAL_SERVER_ERROR => '伺服器錯誤，請聯絡技術人員',   //500

        //用戶端錯誤
        ResponseCodeEnum::DATA_NOT_EXIST => '此項目不存在',
        ResponseCodeEnum::API_PARAMS_ERROR => 'API參數錯誤',
        ResponseCodeEnum::UNAUTHORIZED_ACCESS => '未經授權的訪問',

        //伺服端錯誤

    ],

    MediaTypeEnum::class => [
        MediaTypeEnum::IMAGE => '圖片',
        MediaTypeEnum::VIDEO => '影片',
    ],
];
