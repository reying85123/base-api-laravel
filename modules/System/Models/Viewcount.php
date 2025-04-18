<?php

namespace Modules\System\Models;

use App\Models\Traits\FilterDateTimeTrait;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Viewcount extends Model
{
    use HasFactory, FilterDateTimeTrait;

    protected $table = 'viewcount';

    protected $fillable = [
        'sourceip',
    ];
}
