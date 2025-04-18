<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use \App\Models\Traits\UsesUuid;

class FileStorage extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'file_storages';

    protected $fillable = [
        'origin_name',
        'download_name',
        'file_storage_name',
        'path',
        'file_json',
        'driver',
    ];

    protected $casts = [
        'file_json' => 'object',
    ];

    public function targets()
    {
        return $this->hasMany(FileStorageTarget::class, 'file_storage_id');
    }

    /**
     * Get the fileStoragePath
     *
     * @param  string  $value
     * @return string
     */
    public function getFileStoragePathAttribute()
    {
        return $this->path . '/' . $this->file_storage_name;
    }
}
