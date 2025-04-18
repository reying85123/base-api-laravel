<?php

namespace Modules\Language\Models;

use App\Models\Traits\QueryOrderBy;
use App\Models\Traits\KeywordTrait;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LanguageContentForm extends Model
{
    use HasFactory, SoftDeletes, KeywordTrait, QueryOrderBy;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'usage_type',
        'sequence',
        'is_enable',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_enable' => 'bool',
    ];

    public function languageContentFormFields()
    {
        return $this->hasMany(LanguageContentFormField::class, 'language_content_form_id');
    }
}
