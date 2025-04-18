<?php

namespace Modules\BrowserHistory\Models;

use App\Models\Traits\FilterDateTimeTrait;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrowserHistory extends Model
{
    use HasFactory, FilterDateTimeTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'device_type',
        'platform',
        'sourceip',
        'browser',
        'is_robot',
        'robot_name',
        'link',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_robot' => 'bool',
    ];
}
