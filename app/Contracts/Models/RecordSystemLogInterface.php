<?php

namespace App\Contracts\Models;

interface RecordSystemLogInterface
{
    /**
     * 功能描述
     * 
     * @return string|null
     */
    public function getLogName();

    /**
     * 系統紀錄唯一辨識欄位名稱
     * 
     * @return string|null
     */
    public function getLogKeyName();

    /**
     * 系統紀錄唯一辨識值
     * 
     * @return string|int|null
     */
    public function getLogKeyValue();
}