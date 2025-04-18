<?php

namespace App\Http\Resources\Base\FileStorage;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class FileStorageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $fileInfo = $this->file_json;

        return [
            'id' => $this->id,
            'name' => isset($fileInfo->origin_name) ? $fileInfo->origin_name : "",
            'mime' => isset($fileInfo->file_mime) ? $fileInfo->file_mime : "",
            'size' => isset($fileInfo->size) ? $fileInfo->size : "",
            'url' => $this->getFileUrl(),
        ];
    }

    protected function getFileUrl()
    {
        $url = null;

        switch (config('filesystems.default')) {
            case 's3':
                $url = Storage::url($this->path . '/' . $this->file_storage_name);
                break;
            default:
                $url = route('files', ['file_uuid' => $this->id,'show_type' => 'd']);
                break;
        }

        return $url;
    }
}