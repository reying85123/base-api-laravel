<?php

namespace App\Models\Traits;

use App\Events\Base\ModelRecordSystemLogEvent;

trait RecordSystemLogTrait
{
    protected static $enableCreateLog = true;
    protected static $enableUpdateLog = true;
    protected static $enableDeleteLog = true;

    protected static $logType;

    //需排除系統紀錄的RouteUri
    protected static $excludeSystemLogRouteUri = [];

    protected static function bootRecordSystemLogTrait()
    {
        switch (true) {
            case in_array(request()->getMethod(), ['GET', 'OPTIONS']):
            case in_array(request()->route()->uri(), static::$excludeSystemLogRouteUri):
                return false;
                break;
        }

        if(static::$enableCreateLog){
            static::created(function ($model) {
                ModelRecordSystemLogEvent::dispatch($model, 'create', static::$logType);
            });
        }

        if(static::$enableUpdateLog){
            static::updated(function ($model) {
                ModelRecordSystemLogEvent::dispatch($model, 'update', static::$logType);
            });
        }

        if(static::$enableDeleteLog){
            static::deleted(function ($model) {
                ModelRecordSystemLogEvent::dispatch($model, 'delete', static::$logType);
            });
        }
    }

    public function getLogType(): ?string
    {
        return static::$logType;
    }

    protected static function addExcludeSystemLogRouteUri(array $uris)
    {
        foreach ($uris as $uri) {
            static::$excludeSystemLogRouteUri[] = $uri;
        }
    }
}