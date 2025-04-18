<?php

namespace Modules\Language\Models;

use App\Models\Traits\QueryOrderBy;
use App\Models\Traits\KeywordTrait;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LanguageData extends Model
{
    use HasFactory, SoftDeletes, KeywordTrait, QueryOrderBy;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'language_datas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'data_key',
        'value_type',
        'usage_type',
        'component',
        'i18n_key',
        'label',
        'required',
        'disable',
        'readonly',
        'is_show',
        'xs',
        'sm',
        'md',
        'lg',
        'xl',
        'value',
        'placeholder',
        'locale',
        'sequence'
    ];

    protected $casts = [
        'required' => 'bool',
        'disable' => 'bool',
        'readonly' => 'bool',
        'is_show' => 'bool',
    ];
}
