<?php

namespace Modules\Language\Models;

use App\Models\Traits\QueryOrderBy;
use App\Models\Traits\KeywordTrait;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LanguageContentFormField extends Model
{
    use HasFactory, SoftDeletes, KeywordTrait, QueryOrderBy;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'data_key',
        'value_type',
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
        'placeholder',
        'sequence',
        'language_content_form_id',
    ];

    protected $casts = [
        'required' => 'bool',
        'disable' => 'bool',
        'readonly' => 'bool',
        'is_show' => 'bool',
    ];
}
