<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Common extends Model
{
    use HasFactory;

    protected $fillable = [
        'common_group',
        'name',
        'value',
    ];

    public function parentData(){
        return $this->belongsTo(Common::class, 'parent_id', 'id');
    }
}
