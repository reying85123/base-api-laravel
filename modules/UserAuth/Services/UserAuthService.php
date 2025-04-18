<?php

namespace Modules\UserAuth\Services;

use App\Abstracts\Services\AbstractJwtAuthService;

/**
 * @method static bool|string attempt()
 * @method static \Modules\User\Models\User toUser()
 */
class UserAuthService extends AbstractJwtAuthService
{
    protected static $guard = 'api';
}