<?php

namespace App\Services\ThirdParty\Newebpay\Resources;

class RefundInfoResource
{
    protected $respondType;
    protected $timeStamp;
    protected $merchantOrderNo;
    protected $amt;
    protected $indexType;
    protected $tradeNo;

    public function __construct(array $argc)
    {
        $this->respondType = $argc['respondType'] ?? 'JSON';
        $this->timeStamp = $argc['timeStamp'] ?? time();
        $this->merchantOrderNo = $argc['merchantOrderNo'] ?? null;
        $this->amt = $argc['amt'] ?? null;
        $this->indexType = $argc['indexType'] ?? null;
        $this->tradeNo = $argc['tradeNo'] ?? null;

        if(empty($this->amt) || empty($this->merchantOrderNo) ||
            empty($this->indexType) || empty($this->tradeNo)){
                throw new \Exception('merchantOrderNo、amt、indexType、tradeNo 參數錯誤');
        }

        if(!is_numeric($this->amt)){
            throw new \Exception('amt 參數型態錯誤');
        }

        if(!is_numeric($this->indexType) && !($this->indexType === 1 || $this->indexType === 2)){
            throw new \Exception('indexType 參數型態錯誤');
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
            'IndexType' => $this->indexType,
            'TradeNo' => $this->tradeNo,
            'CloseType' => 2,
        ];

        return $result;
    }
}