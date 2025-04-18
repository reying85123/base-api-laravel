<?php

namespace App\Contracts\Services;

interface AuthServiceInterface
{
    /**
     * 取得驗證門衛
     * 
     * @return \Illuminate\Contracts\Auth\Factory|\Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     */
    public static function guard();

    /**
     * 登入驗證
     *
     * @param array $credentials
     * 
     * @return boolean
     */
    public static function attempt(array $credentials = []);

    /**
     * 取得人員
     * 
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function toUser();
}