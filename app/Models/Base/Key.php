<?php

namespace App\Models\Base;

use App\Models\Traits\QueryOrderBy;
use App\Models\Traits\KeywordTrait;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Key extends Model
{
    use HasFactory, SoftDeletes, QueryOrderBy, KeywordTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'prefix',
        'code',
        'value',
        'is_enable',
        'sequence',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_enable' => 'bool',
    ];
}
