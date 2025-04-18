<?php

namespace App\Imports\Traits;

/**
 * 
 */
trait ImportTitle
{
    protected $titleFormat;
    protected $title;
    protected $titleRow = 1;
    protected $skipTitle = 1;

    public function getTitle(){
        return $this->title;
    }

    protected function setSheetTitle(\Maatwebsite\Excel\Sheet $sheet){
        $this->title = collect($sheet->getDelegate()->getCoordinates())
            ->filter(function($coordinate){
                return preg_match("/^[a-zA-Z]+{$this->titleRow}$/", $coordinate) === 1;
            })->map(function($coordinate) use ($sheet){
                return $sheet->getDelegate()->getCell($coordinate)->getValue();
            })
            ->skip($this->skipTitle)
            ->map(function($title){
                abort_if(!($this->titleFormat[$title] ?? null), 400, '不合法的標題名稱，請勿修改匯入資料標題名稱');
                return collect([
                    'text' => $title,
                    'field' => $this->titleFormat[$title],
                ]);
            })->values();
    }
}
