<?php

namespace Modules\FrontendMenu\Models\Traits;

trait FrontendMenuTrait
{
    /**
     * @param   \Illuminate\Database\Eloquent\Builder $query
     * @return    \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDefaultFrontFilter($query)
    {
        $query->whereIsEnable(true);
        return $query;
    }
}
