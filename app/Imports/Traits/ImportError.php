<?php

namespace App\Imports\Traits;

/**
 * 
 */
trait ImportError
{
    protected $errorReport = [];

    public function getImportError(): array{
        return collect($this->errorReport)->map(function($errorLog, $errorType){
            $errorLog = collect($errorLog);
            
            return [
                'type' => $errorType,
                'rows' => $errorLog->pluck('row')->toArray(),
                'messages' => $errorLog->pluck('msg')->toArray(),
            ];
        })->values()->toArray();
    }

    protected function reportPattern($rowNum, $data)
    {
        $dataRow = $rowNum + $this->startRow;
        return ['row' => $dataRow, 'msg' => "第{$dataRow}行, $data"];
    }

    protected function addErrorReport($errorType, $rowNum, $data){
        if(isset($this->errorReport[$errorType])){
            array_push($this->errorReport[$errorType], $this->reportPattern($rowNum, $data));
        }
    }
}
