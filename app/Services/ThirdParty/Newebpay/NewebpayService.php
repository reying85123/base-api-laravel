<?php

namespace App\Services\ThirdParty\Newebpay;

/**
 * 藍新金流
 */

use GuzzleHttp\Client as HttpClient;

use App\Services\ThirdParty\Newebpay\Responses\Response;

use App\Services\ThirdParty\Newebpay\Resources\TradeInfoResource;
use App\Services\ThirdParty\Newebpay\Resources\RefundInfoResource;
use App\Services\ThirdParty\Newebpay\Resources\CancelInfoResource;
use App\Services\ThirdParty\Newebpay\Resources\QueryOrderResource;

class NewebpayService
{
    const API_HOST = 'https://core.newebpay.com';
    const TEST_API_HOST = 'https://ccore.newebpay.com';

    protected static $appServices = [
        'order' => 'MPG/mpg_gateway',
        'getOrder' => 'API/QueryTradeInfo',
        'cancel' => 'API/CreditCard/Cancel',
        'refund' => 'API/CreditCard/Close',
    ];

    /**
     * 金流串接金鑰
     *
     * @var string
     */
    protected $key;

    /**
     * 金流串接IV
     *
     * @var string
     */
    protected $iv;

    /**
     * 商店代號
     *
     * @var string
     */
    protected $mid;

    protected $testMode;
    protected $actionHost;
    protected $httpClient;

    public function __construct(array $argc = [])
    {
        $merchantID = $argc['merchantID'] ?? config('newebpay.merchant_id');
        $hashKey = $argc['hashKey'] ?? config('newebpay.hash_key');
        $hashIv = $argc['hashIv'] ?? config('newebpay.hash_iv');
        $this->testMode = $argc['testMode'] ?? config('newebpay.test_mode');

        if(empty($merchantID) || empty($hashKey) || empty($hashIv)){
            throw new \Exception('merchantID、hashKey、hashIv 參數錯誤');
        }

        $this->key = $hashKey;
        $this->iv = $hashIv;
        $this->mid = $merchantID;

        $this->actionHost = $this->testMode ? static::TEST_API_HOST: static::API_HOST;

        $this->httpClient = new HttpClient([
            'base_uri' => $this->actionHost,
        ]);
    }

    /**
     * Request Handlder
     *
     * @param string $method    GET、POST、PATCH、DELETE
     * @param array $postData   POST資料
     * @param array $queryParams
     * @return Response
     */
    protected function requestHandler($method, $uri, $postData, $queryParams=null)
    {
        $options = [];

        $headers = [];

        $options['headers'] = $headers;

        if ($queryParams) {
            $options['query'] = $queryParams;
        }

        $options['form_params'] = $postData;

        try {
            $response = $this->httpClient->request($method, $uri, $options);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
        }

        return new Response($response);
    }

    public function verifyCheckCode($encryptData, $checkCode): bool
    {
        return $checkCode === $this->getCheckCode($encryptData);
    }

    public function getCheckValue($encryptData): string
    {
        $hashs = "IV=" . $this->iv . "&" . $encryptData . "&Key=" . $this->key;
        return strtoupper(hash("sha256",$hashs));
    }

    public function getCheckCode($encryptData): string
    {
        $hashs = "HashKey=" . $this->key . "&" . $encryptData . "&HashIV=" . $this->iv;
        return strtoupper(hash("sha256",$hashs));
    }

    protected function dataEncrypt($data): string
    {
        $queryData = http_build_query($data);
        return bin2hex(openssl_encrypt($queryData, "AES-256-CBC", $this->key, OPENSSL_RAW_DATA, $this->iv));
    }

    private function strippadding($string)
    {
        $slast = ord(substr($string, -1));
        $slastc = chr($slast);
        $pcheck = substr($string, -$slast);
        if (preg_match("/$slastc{" . $slast . "}/", $string)) {
            $string = substr($string, 0, strlen($string) - $slast);
            return $string;
        } else {
            return false;
        }
    }

    protected function dataDecrypt($data): string
    {
        return $this->strippadding(openssl_decrypt(hex2bin($data), "AES-256-CBC", $this->key, OPENSSL_RAW_DATA|OPENSSL_ZERO_PADDING, $this->iv));
    }

    public function getDecryptData($encryptData)
    {
        $data = $this->dataDecrypt($encryptData);
        return !!$data ? json_decode($data, true): null;
    }

    /**
     * 建立訂單表單資料
     *
     * @param TradeInfoResource $tradeInfo
     * @return array
     */
    public function createOrderBody(TradeInfoResource $tradeInfo)
    {
        $tradeInfoBody = array_merge($tradeInfo->build(), [
            'MerchantID' => $this->mid,
        ]);

        $tradeInfoEncrypt = $this->dataEncrypt($tradeInfoBody);

        $body = [
            'MerchantID' => $this->mid,
            'Version' => '2.0',
            'TradeInfo' => $tradeInfoEncrypt,
            'TradeSha' => $this->getCheckCode($tradeInfoEncrypt),
            'action' => "{$this->actionHost}/" . static::$appServices['order'],
        ];

        return $body;
    }

    /**
     * 訂單退款
     *
     * @param RefundInfoResource $refundInfo
     * @return Response
     */
    public function orderRefund(RefundInfoResource $refundInfo)
    {
        $data = [
            'MerchantID_' => $this->mid,
            'PostData_' => $this->dataEncrypt($refundInfo->build()),
        ];

        return $this->requestHandler('POST', static::$appServices['refund'], $data);
    }

    /**
     * 取消授權
     *
     * @param CancelInfoResource $cancelInfo
     * @return Response
     */
    public function orderCancel(CancelInfoResource $cancelInfo)
    {
        $data = [
            'MerchantID_' => $this->mid,
            'PostData_' => $this->dataEncrypt($cancelInfo->build()),
        ];

        return $this->requestHandler('POST', static::$appServices['cancel'], $data);
    }

    /**
     * 查詢訂單
     *
     * @param QueryOrderResource $queryData
     * @return Response
     */
    public function getOrder(QueryOrderResource $queryData)
    {
        $queryDataBody = array_merge($queryData->build(), [
            'MerchantID' => $this->mid,
        ]);

        $checkValueBody = [
            "Amt={$queryDataBody['Amt']}",
            "MerchantID={$this->mid}",
            "MerchantOrderNo={$queryDataBody['MerchantOrderNo']}",
        ];

        $data = array_merge($queryDataBody, [
            'CheckValue' => $this->getCheckValue(join('&', $checkValueBody))
        ]);

        return $this->requestHandler('POST', static::$appServices['getOrder'], $data);
    }
}