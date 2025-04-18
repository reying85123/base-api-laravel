<?php

namespace App\Models\Base;

use App\Models\Traits\QueryOrderBy;
use App\Models\Traits\KeywordTrait;

use Modules\User\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotify extends Model
{
    use HasFactory, KeywordTrait, QueryOrderBy;

    protected $fillable = [
        'title',
        'description',
        'is_read',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
