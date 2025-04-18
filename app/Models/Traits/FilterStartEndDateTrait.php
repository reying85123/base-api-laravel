<?php

namespace App\Models\Traits;

trait FilterStartEndDateTrait
{
    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $startColumnName
     * @param string $endColumnName
     * @param string|null $startDate
     * @param string|null $endDate
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function scopeStartEndDate($query, $startColumnName, $endColumnName, $startDate = null, $endDate = null)
    {
        return $query->where(function ($query) use ($startColumnName, $endColumnName, $startDate, $endDate) {
            // 當 startDate 和 endDate 都有值的情況
            if ($startDate && $endDate) {
                $query->orWhere(function ($query) use ($startColumnName, $endColumnName, $startDate, $endDate) {
                    $query->where($startColumnName, '<=', $endDate)
                        ->where($endColumnName, '>=', $startDate);
                });
                $query->orWhere(function ($query) use ($startColumnName, $endColumnName, $endDate) {
                    $query->where($startColumnName, '<=', $endDate)
                        ->whereNull($endColumnName);
                });
            }

            // 只有 endDate 有值的情況
            if ($endDate && !$startDate) {
                $query->orWhere(function ($query) use ($startColumnName, $endDate) {
                    $query->where($startColumnName, '<=', $endDate);
                });
            }

            // 只有 startDate 有值的情況
            if ($startDate && !$endDate) {
                $query->orWhere(function ($query) use ($endColumnName, $startDate) {
                    $query->where($endColumnName, '>=', $startDate);
                });
                $query->orWhere(function ($query) use ($endColumnName) {
                    $query->whereNull($endColumnName);
                });
            }
        });
    }
}
