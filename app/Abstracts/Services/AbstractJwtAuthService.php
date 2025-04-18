<?php

namespace App\Abstracts\Services;

/**
 * @method static \PHPOpenSourceSaver\JWTAuth\JWTGuard guard()
 */
abstract class AbstractJwtAuthService extends AbstractAuthService
{
    public static function getPayLoad()
    {
        return static::guard()->payload();
    }
}