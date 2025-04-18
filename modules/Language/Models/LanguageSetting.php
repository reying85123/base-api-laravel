<?php

namespace Modules\Language\Models;

use App\Models\Traits\QueryOrderBy;
use App\Models\Traits\KeywordTrait;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LanguageSetting extends Model
{
    use HasFactory, SoftDeletes, KeywordTrait, QueryOrderBy;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'locale',
        'sequence',
        'is_enable',
        'is_client_enable',
        'is_admin_enable',
        'is_default',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_enable' => 'bool',
        'is_client_enable' => 'bool',
        'is_admin_enable' => 'bool',
        'is_default' => 'bool',
    ];
}
