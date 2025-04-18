<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileStorageTarget extends Model
{
    use HasFactory;

    protected $table = 'file_storage_target';

    protected $fillable = [];
}
