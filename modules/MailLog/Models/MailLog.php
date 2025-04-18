<?php

namespace Modules\MailLog\Models;

use App\Models\Traits\KeywordTrait;
use App\Models\Traits\QueryOrderBy;
use App\Models\Traits\FilterDateTimeTrait;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailLog extends Model
{
    use HasFactory, KeywordTrait, QueryOrderBy, FilterDateTimeTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'from',
        'to',
        'cc',
        'bcc',
        'reply_to',
        'subject',
        'send_datetime',
        'content',
        'state',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'send_datetime' => 'date:c',
    ];

    /**
     * Set the sendDatetime
     *
     * @param  string  $date
     * @return string
     */
    public function setSendDatetimeAttribute($date)
    {
        $this->attributes['send_datetime'] = $date ? getDateTime($date): null;
    }
}
