<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Schema;

/**
 * @method \Illuminate\Database\Eloquent\Builder queryOrderBy(string $orderString)
 */
trait QueryOrderBy
{

    /**
     * 自訂排序公式
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $orderString
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeQueryOrderBy($query, $orderString)
    {
        if(empty($orderString)){
            abort(400, '排序字串不可為空');
        }

        //比對Model與Schema當前資料庫設定，若不一致，切換成Model資料庫設定
        $schemaBuilder = Schema::getConnection()->getSchemaBuilder();

        collect(Schema::getConnection()->getConfig())
            ->each(function($configValue, $configKey) use (&$schemaBuilder) {
                if($configValue !== data_get($this->getConnection()->getConfig(), $configKey)){
                    $schemaBuilder = Schema::setConnection($this->getConnection());
                    return;
                }
            });

        //字串轉小寫，移除前後空白
        $orderBy = collect(explode(',', trim(strtolower($orderString))));

        $orderBy->each(function($orderByString) use ($query, $schemaBuilder){
            $orderByString = collect(explode(':', trim($orderByString)));
            $columnName = trim($orderByString[0]);
            if(!$schemaBuilder->hasColumn($this->getTable(), $columnName)){
                abort(400, '找無此欄位');
            }

            if(isset($orderByString[1])){
                $sort = trim($orderByString[1]);
                if($sort == 'desc' || $sort == 'asc'){
                    $query->orderBy($columnName, $sort);
                }else{
                    abort(400, '排序規則錯誤');
                }
            }else{
                $query->orderBy($columnName);
            }
        });


        return $query;
    }
}
