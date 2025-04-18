<?php

namespace Modules\Relationship\Models;

use App\Models\Traits\QueryOrderBy;
use App\Models\Traits\KeywordTrait;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Relationship extends Model
{
    use HasFactory, SoftDeletes, QueryOrderBy, KeywordTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group',
        'start_date',
        'end_date',
        'priority',
        'remarks',
        'direction_type',
    ];

    public function entity()
    {
        return $this->morphTo();
    }

    public function relatedEntity()
    {
        return $this->morphTo();
    }
}
