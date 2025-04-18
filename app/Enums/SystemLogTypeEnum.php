<?php

namespace App\Enums;

use Jiannei\Enum\Laravel\Enum;

class SystemLogTypeEnum extends Enum
{
    const USER_LOGIN_LOGOUT = 'user_login_logout';
    const USER_CREATED = 'user_created';
    const USER_UPDATED = 'user_updated';
    const USER_DELETED = 'user_deleted';
}
