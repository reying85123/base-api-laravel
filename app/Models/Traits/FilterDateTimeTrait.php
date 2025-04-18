<?php

namespace App\Models\Traits;

trait FilterDateTimeTrait
{
    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $columnName
     * @param string $startDate
     * @param boolean $inclusion
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function scopeStartOf($query, $columnName, $startDate, $inclusion = true){
        return $query->where($columnName, ($inclusion ? '>=': '>'), $startDate);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $columnName
     * @param string $endDate
     * @param boolean $inclusion
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function scopeEndOf($query, $columnName, $endDate, $inclusion = true){
        return $query->where($columnName, ($inclusion ? '<=': '<'), $endDate);
    }
}