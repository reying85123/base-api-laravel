<?php

namespace Modules\VerifyCode\Models;

use App\Models\Traits\QueryOrderBy;
use App\Models\Traits\KeywordTrait;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifyCode extends Model
{
    use HasFactory, QueryOrderBy, KeywordTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'verify_codes';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account',
        'type',
        'phone',
        'email',
        'token',
        'member_account_id',
        'jwt_token',
    ];

    /**
     * Get the isExpired
     *
     * @param  string  $value
     * @return string
     */
    public function getIsExpiredAttribute()
    {
        //時效3分鐘
        return strtotime($this->updated_at) + 180 < time();
    }
}
