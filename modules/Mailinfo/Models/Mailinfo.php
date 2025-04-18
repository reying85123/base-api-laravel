<?php

namespace Modules\Mailinfo\Models;

use App\Models\Traits\KeywordTrait;
use App\Models\Traits\QueryOrderBy;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mailinfo extends Model
{
    use HasFactory, KeywordTrait, QueryOrderBy;

    protected $table = 'mailinfo';

    protected $fillable = [
        'subject',
        'fromname',
        'repeatname',
        'repeatmail',
        'tomail',
        'cc',
        'bcc',
        'content_json',
        'content',
    ];

    /**
     * Set the Cc
     *
     * @param  string  $value
     * @return string
     */
    public function setCcAttribute($value)
    {
        $this->attributes['cc'] = $value !== null ?
            join(',', array_map('trim', explode(',', $value))) : null;
    }

    /**
     * Set the Bcc
     *
     * @param  string  $value
     * @return string
     */
    public function setBccAttribute($value)
    {
        $this->attributes['bcc'] = $value !== null ?
            join(',', array_map('trim', explode(',', $value))) : null;
    }
}
