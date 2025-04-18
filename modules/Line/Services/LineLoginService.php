<?php

namespace Modules\Line\Services;

use App\Abstracts\Services\HttpClient\AbstractsHttpClientService;

use Illuminate\Support\Arr;

class LineLoginService extends AbstractsHttpClientService
{
    const API_HOST = 'https://api.line.me';

    protected static $appServices = [
        'profile' => '/v2/profile',
        'verify' => '/oauth2/v2.1/verify',
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
    public function getProfile($userAccessToken)
    {
        if (!!$profile = data_get($this->dataCache, "profile.{$userAccessToken}", null)) {
            return $profile;
        }

        $headers = [
            'Authorization' => "Bearer {$userAccessToken}"
        ];

        return $this->dataCache['profile'][$userAccessToken] = $this->requestHandler('GET', static::$appServices['profile'], [], $headers);
    }

    /**
     * 取得使用者資訊
     *
     * @param string $userAccessToken
     * @return Response
     */
    public function verify($idToken)
    {
        if (!!$verify = data_get($this->dataCache, "verify.{$idToken}", null)) {
            return $verify;
        }

        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
        $optBody = ['form_params' => ['id_token' => $idToken, 'client_id' => $this->channelId]];
        return $this->dataCache['verify'][$idToken] = $this->requestHandler('POST', static::$appServices['verify'], $optBody, $headers);
    }
}
