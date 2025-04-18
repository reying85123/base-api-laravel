<?php

namespace Modules\System\Models;

use Modules\User\Models\User;

use App\Models\Traits\QueryOrderBy;
use App\Models\Traits\KeywordTrait;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Enums\SystemLogTypeEnum;

class SystemLog extends Model
{
    use HasFactory, KeywordTrait, QueryOrderBy;

    protected $table = 'system_logs';

    protected $fillable = [
        'title',
        'description',
        'type',
        'sourceip',
    ];

    /**
     * Get the user that owns the SystemLog
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
