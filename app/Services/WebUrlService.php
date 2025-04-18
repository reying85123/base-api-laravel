<?php

namespace App\Services;

class WebUrlService
{
    public static function generateUrl($routeName, $webConfig = 'default', $options = [])
    {
        $webConfig = config('web.sites.' . ($webConfig === 'default' ? config('web.default'): $webConfig));

        $baseUrl = data_get($webConfig, 'url');

        //替換網址文字
        if(isset($options['url_replace'])){
            foreach ($options['url_replace'] as $key => $value) {
                $baseUrl = str_replace("{{{$key}}}", $value, $baseUrl);
            }
        }

        $url = $baseUrl . data_get($webConfig, "routes.{$routeName}", '');

        //網址轉小寫
        $url = strtolower($url);

        //網址Query Params
        if(isset($options['params'])){
            $url .= '?' . collect($options['params'])->map(function($value, $key){
                return "$key=" . urlencode($value);
            })->join('&');
        }

        return $url;
    }
}
