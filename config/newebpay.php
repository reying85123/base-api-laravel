<?php

return [
    /*
    |---------------------------------------------------------------
    | 藍新金流參數
    |---------------------------------------------------------------
    | 
    | test_mode      設定金流是否為測試模式
    | merchant_id    商家代號
    | hash_key       加密用Key
    | hash_iv        加密用IV
    | notify_url     金流後端回傳URL
    */

    /*
    | 
    | 設定金流是否為測試模式
    | 
    */

    'test_mode' => env('NEWEBPAY_TEST_MODE', true),

    /*
    | 
    | 商家代號
    | 
    */

    'merchant_id' => env('NEWEBPAY_MERCHANT_ID', null),

    /*
    | 
    | 加密用Key
    | 
    */

    'hash_key' => env('NEWEBPAY_HASH_KEY', null),

    /*
    | 
    | 加密用IV
    | 
    */

    'hash_iv' => env('NEWEBPAY_HASH_IV', null),

    /*
    | 
    | 金流後端回傳URL
    | 
    */

    'notify_url' => env('NEWEBPAY_NOTIFY_URL', ''),
];