<?php

namespace App\Models\Traits;

use App\Services\SerialNoService;

trait SerialNumber
{
    protected $serialNumberColumn = 'serial_number';

    protected static function bootSerialNumber() {
        static::creating(function ($model) {
            $model->{$model->serialNumberColumn} = SerialNoService::nextSrialNo(
                $model->getNoType(),
                $model->getNoLength(),
                $model->getNoFormat(),
                $model->getNoIsReset(),
            );
        });
    }

    protected function getNoType(){
        return 'A';
    }

    protected function getNoLength(){
        return 5;
    }

    protected function getNoFormat(){
        return '{0}{1}{2}';
    }

    protected function getNoIsReset(){
        return false;
    }
}