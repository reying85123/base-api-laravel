<?php

namespace Modules\City\Models;

use App\Models\Traits\QueryOrderBy;
use App\Models\Traits\KeywordTrait;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory, QueryOrderBy, KeywordTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'commons';

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        
        static::addGlobalScope('common_group', function(Builder $builder){
            $builder->where('common_group', 'address')->where('parent_id', null);
        });
    }
}
