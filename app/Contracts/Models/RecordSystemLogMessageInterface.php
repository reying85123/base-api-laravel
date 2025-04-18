<?php

namespace App\Contracts\Models;

interface RecordSystemLogMessageInterface
{
    /**
     * 客製化系統紀錄訊息
     * 
     * e.g. ["{Action}" => "{Message}"]
     * 
     * 系統Action，create => 新增、update => 更新、delete => 刪除
     * 
     * 訊息之替換文字，使用時前後加上#符號
     * item => 功能描述
     * itemKeyName => 唯一辨識欄位名稱
     * itemKey => 唯一辨識值
     * 
     * e.g. ["create" => "create #item#, #itemKeyName# = #itemKey#"]
     *
     * @return array
     */
    public function getLogMessages(): array;

    /**
     * 新增替換文字
     * 
     * 參數前後請帶入#
     * 
     * e.g. ["#attribute#" => "value"]
     *
     * @return array
     */
    public function addMessageAttributes(): array;
}