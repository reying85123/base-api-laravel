<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SerialNo extends Model
{
    use HasFactory;

    protected $table = 'serial_no';

    public $timestamps = false;

    protected $fillable = [
        'no_type',
        'year_month_date',
        'seq_no',
    ];

    public $str_len = 3;

    public function getSerialNoAttribute(){
        return str_pad($this->seq_no, $this->str_len, '0', STR_PAD_LEFT);
    }

    public function getFormatArrayAttribute(){
        return [
            $this->no_type,
            $this->year_month_date,
            $this->serial_no,
        ];
    }
}
