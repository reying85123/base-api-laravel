<?php

namespace App\Services\ThirdParty\Newebpay\Resources;

class QueryOrderResource
{
    protected $respondType;
    protected $timeStamp;
    protected $merchantOrderNo;
    protected $amt;

    public function __construct(array $argc)
    {
        $this->respondType = $argc['respondType'] ?? 'JSON';
        $this->timeStamp = $argc['timeStamp'] ?? time();
        $this->merchantOrderNo = $argc['merchantOrderNo'] ?? null;
        $this->amt = $argc['amt'] ?? null;

        if(empty($this->amt) || empty($this->merchantOrderNo)){
                throw new \Exception('merchantOrderNo、amt、tradeNo 參數錯誤');
        }

        if(!is_numeric($this->amt)){
            throw new \Exception('amt 參數型態錯誤');
        }
    }

    public function build()
    {
        $result = [
            'RespondType' => $this->respondType,
            'TimeStamp' => $this->timeStamp,
            'Version' => '2.0',
            'MerchantOrderNo' => $this->merchantOrderNo,
            'Amt' => $this->amt,
        ];

        return $result;
    }
}