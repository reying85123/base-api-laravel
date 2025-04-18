<?php

namespace Modules\Facebook\Services;

use App\Abstracts\Services\HttpClient\AbstractsHttpClientService;

use Illuminate\Support\Arr;

class FacebookLoginService extends AbstractsHttpClientService
{
    const API_HOST = 'https://graph.facebook.com';

    protected static $appServices = [];

    protected $channelId;

    protected $channelSecret;

    protected $scope;

    protected $httpClient;

    protected $dataCache = [];

    public function __construct($optParams)
    {
        if (!Arr::has($optParams, ['channelId', 'channelSecret'])) {
            throw new \Exception('未設定channelId、channelSecret，無法使用');
        }

        ['channelId' => $this->channelId, 'channelSecret' => $this->channelSecret, 'scope' => $this->scope] = $optParams;

        $baseUri = self::API_HOST;

        $headers = [
            'Content-Type' => 'multipart/form-data',
        ];

        $this->setHttpClient([
            'base_uri' => $baseUri,
            'headers' => $headers,
            'timeout' => 15.0,
        ]);
    }
}
