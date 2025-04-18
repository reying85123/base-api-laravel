<?php

namespace App\Services\ThirdParty\Newebpay\Resources;

use App\Services\ThirdParty\Newebpay\Repositories\Enums\PaymentTypeEnum;

class TradeInfoResource
{
    protected $respondType;
    protected $timeStamp;
    protected $merchantOrderNo;
    protected $amt;
    protected $itemDesc;
    protected $notifyUrl;
    protected $customerUrl;
    protected $clientBackUrl;

    protected $instFlag;

    protected $payMethod;

    public function __construct(array $argc)
    {
        $this->respondType = $argc['respondType'] ?? 'JSON';
        $this->timeStamp = $argc['timeStamp'] ?? time();
        $this->merchantOrderNo = $argc['merchantOrderNo'] ?? null;
        $this->amt = $argc['amt'] ?? null;
        $this->itemDesc = $argc['itemDesc'] ?? null;
        $this->notifyUrl = $argc['notifyUrl'] ?? null;
        $this->customerUrl = $argc['customerUrl'] ?? null;
        $this->clientBackUrl = $argc['clientBackUrl'] ?? null;

        $this->instFlag = $argc['instFlag'] ?? null;
        $this->payMethod = $argc['payMethod'] ?? null;

        if(empty($this->respondType) || empty($this->timeStamp) ||
            empty($this->merchantOrderNo) || empty($this->itemDesc)){
                throw new \Exception('merchantTradeNo、merchantTradeDate、paymentType、totalAmount、tradeDesc、itemName、returnUrl 參數錯誤');
        }

        // if(!PaymentTypeEnum::hasValue($this->payMethod)){
        //     throw new \Exception('付款方式設定錯誤');
        // }
    }

    public function build()
    {
        $result = [
            'RespondType' => $this->respondType,
            'TimeStamp' => $this->timeStamp,
            'Version' => '2.0',
            'MerchantOrderNo' => $this->merchantOrderNo,
            'Amt' => $this->amt,
            'ItemDesc' => $this->itemDesc,
            'NotifyURL' => $this->notifyUrl,
            'ClientBackURL' => $this->clientBackUrl,
        ];

        if(strlen($result['ItemDesc']) > 50){
            $itemDesc = mb_str_split($result['ItemDesc'], 50);
            $itemDesc = mb_str_split(array_shift($itemDesc), 25);
            $result['ItemDesc'] = array_shift($itemDesc);
        }

        if($this->payMethod === PaymentTypeEnum::CREDIT_SINGLE){
            $result += [
                'CREDIT' => 1,
            ];
        }

        if($this->payMethod === PaymentTypeEnum::CREDIT_INSTALLMENT){
            if(empty($this->instFlag)){
                throw new \Exception('參數設定錯誤');
            }
            $result += [
                'InstFlag' => $this->instFlag,
            ];
        }

        if($this->payMethod === PaymentTypeEnum::ATM){
            $result += [
                'VACC' => 1,
            ];

            if(!empty($this->customerUrl)){
                $result += [
                    'CustomerURL' => $this->customerUrl,
                ];
            }
        }

        return $result;
    }
}