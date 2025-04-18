<?php

namespace App\Services;

use App\Models\Base\SerialNo;
use Illuminate\Support\Facades\DB;
use MessageFormatter;

class SerialNoService
{
    public static function nextSrialNo($no_type, $no_len = 5, $format = '{0}{1}{2}', $is_reset = false){

        $year_month_date = mb_substr(date('Ymd'), 2, strlen(date('Ymd')), "utf-8");

        DB::beginTransaction();

        $check_serial_no = SerialNo::where([
                                'no_type' => $no_type,
                                'year_month_date' => $year_month_date,
                            ])
                            ->lockForUpdate()
                            ->exists();

        if(!$check_serial_no){
            //建立流水號
            $serial_no = new SerialNo([
                'no_type' => $no_type,
                'year_month_date' => $year_month_date,
                'seq_no' => 1,
            ]);
        }else{
            //更新或重置流水號
            $serial_no = SerialNo::where([
                            'no_type' => $no_type,
                            'year_month_date' => $year_month_date,
                        ])->first();

            if($is_reset){
                $serial_no->seq_no = 1;
            }else{
                $serial_no->seq_no += 1;
            }
        }

        $serial_no->save();

        DB::commit();

        $serial_no->str_len = $no_len;

        return MessageFormatter::formatMessage(config('app.locale'), $format, $serial_no->format_array);
    }
}
