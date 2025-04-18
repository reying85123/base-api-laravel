<?php

namespace Modules\Google\Services;

use App\Abstracts\Services\HttpClient\AbstractsHttpClientService;

use Illuminate\Support\Arr;

class GoogleLoginService extends AbstractsHttpClientService
{
    const API_HOST = 'https://www.googleapis.com/oauth2';

    protected static $appServices = [
        'userinfo' => '/v3/userinfo',
    ];

    protected $channelId;

    protected $channelSecret;

    protected $scope;

    protected $httpClient;

    protected $dataCache = [];

    protected $baseUri;

    public function __construct($optParams)
    {
        if (!Arr::has($optParams, ['channelId', 'channelSecret'])) {
            throw new \Exception('未設定channelId、channelSecret，無法使用');
        }

        ['channelId' => $this->channelId, 'channelSecret' => $this->channelSecret, 'scope' => $this->scope] = $optParams;

        $this->baseUri =  self::API_HOST;

        $headers = [
            'Content-Type' => 'multipart/form-data',
        ];

        $this->setHttpClient([
            'base_uri' => $this->baseUri,
            'headers' => $headers,
            'timeout' => 15.0,
        ]);
    }

    /**
     * 取得使用者資訊
     *
     * @param string $userAccessToken
     * @return Response
     */
    public function getUserinfo($userAccessToken)
    {
        if (!!$userinfo = data_get($this->dataCache, "userinfo.{$userAccessToken}", null)) {
            return $userinfo;
        }

        $headers = [
            'Authorization' => "Bearer {$userAccessToken}"
        ];

        return $this->dataCache['userinfo'][$userAccessToken] = $this->requestHandler('GET', static::$appServices['userinfo'], [], $headers);
    }
}
