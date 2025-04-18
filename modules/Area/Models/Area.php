<?php

namespace Modules\Area\Models;

use App\Models\Traits\QueryOrderBy;
use App\Models\Traits\KeywordTrait;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory, QueryOrderBy, KeywordTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'commons';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'value' => 'object',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        
        static::addGlobalScope('common_group', function(Builder $builder){
            $builder->where('common_group', 'address')->where('parent_id', '!=', null);
        });
    }
}
