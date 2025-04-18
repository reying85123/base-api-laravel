<?php

namespace App\Abstracts\Services;

use App\Contracts\Services\AuthServiceInterface;

use Illuminate\Contracts\Auth\Authenticatable;

abstract class AbstractAuthService implements AuthServiceInterface
{
    protected static $guard;

    public static function guard()
    {
        if (empty(static::$guard)) {
            throw new \Exception('尚未設定權限門衛');
        }

        return auth(static::$guard);
    }

    public static function attempt(array $credentials = [])
    {
        return static::guard()->attempt($credentials);
    }

    public static function toUser()
    {
        return static::guard()->user();
    }

    public static function setUser(Authenticatable $user)
    {

        return static::guard()->setUser($user);
    }
}
