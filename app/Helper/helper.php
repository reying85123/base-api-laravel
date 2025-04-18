<?php
    use Illuminate\Support\Carbon;

    if (! function_exists('getStartEndDateTimeOfType')) {
        function getStartEndDateTimeOfType($type, $format = null, $sub_count = 0){
            $start = null;
            $end = null;

            switch ($type) {
                case 'day':
                    $start = Carbon::now()->subDays($sub_count)->startOfDay();
                    $end = Carbon::now()->subDays($sub_count)->endOfDay();
                    break;
                case "week":
                    $start = Carbon::now()->subWeeks($sub_count)->startOfWeek();
                    $end = Carbon::now()->subWeeks($sub_count)->endOfWeek();
                    break;
                case "month":
                    $start = Carbon::now()->subMonths($sub_count)->startOfMonth();
                    $end = Carbon::now()->subMonths($sub_count)->endOfMonth();
                    break;
                case "season":
                    $start = Carbon::now()->subQuarters($sub_count)->startOfQuarter();
                    $end = Carbon::now()->subQuarters($sub_count)->endOfQuarter();
                    break;
                case "year":
                    $start = Carbon::now()->subYears($sub_count)->startOfYear();
                    $end = Carbon::now()->subYears($sub_count)->endOfYear();
                    break;
                default:
                    throw new Exception("Use Error Type");
                    break;
            }

            return [
                "start_datetime" => $format ? $start->format($format): $start->toDateTimeString(),
                "end_datetime" => $format ? $end->format($format): $end->toDateTimeString()
            ];
        }
    }

    if (! function_exists('getDateTime')) {
        function getDateTime($time = null, $format = null, $timezone = null, $return_to_object = false){
            $timezone = $timezone ?: config('app.timezone', 'Asia/Taipei');

            $datetime = $time ? Carbon::parse($time): Carbon::now();
            $datetime->setTimezone($timezone);

            if(is_bool($format) && $format){
                $return_to_object = $format;
            }

            return $return_to_object ? $datetime : ($format ? $datetime->format($format): $datetime->toDateTimeString());
        }
    }

    if(!function_exists('parseBoolean')){
        function parseBoolean($val, $return_null=false){
            $boolval = ( is_string($val) ? filter_var($val, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : (bool) $val );
            return ( $boolval===null && !$return_null ? false : $boolval );
        }
    }

    if(!function_exists('excelColumnRange')){
        function excelColumnRange($lower, $upper) {
            $columns = [];
            ++$upper;

            $lower = strtoupper($lower);
            $upper = strtoupper($upper);
            
            for ($i = $lower; $i !== $upper; ++$i) {
                $columns[] = $i;
            }

            return $columns;
        }
    }