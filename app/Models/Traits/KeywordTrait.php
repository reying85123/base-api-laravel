<?php

namespace App\Models\Traits;

trait KeywordTrait
{
    use LikeScope;

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|array $columns
     * @param string $word
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereKeyword($query, $columns, $word){
        $columns = is_array($columns) ? $columns: [$columns];

        $query->where(function($query) use ($columns, $word){
            foreach ($columns as $column) {
                $query->orWhere->like($column, $word);
            }
        });

        return $query;
    }
}