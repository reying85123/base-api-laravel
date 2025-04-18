<?php

namespace App\Services;

use Mews\Captcha\Captcha;

class CaptchaService
{
    public static function generateCaptcha($captchaType = 'default', $api = false)
    {
        $captcha = app(Captcha::class);

        $captchaInfo = $captcha->create($captchaType, $api);

        return $captchaInfo;
    }
    
    public static function generateApiCaptcha($captchaType = 'default')
    {
        return static::generateCaptcha($captchaType, true);
    }

    public static function validateCaptcha($value, $key, $captchaType = 'default')
    {
        $captcha = app(Captcha::class);

        return $captcha->check_api($value, $key, $captchaType);
    }
}