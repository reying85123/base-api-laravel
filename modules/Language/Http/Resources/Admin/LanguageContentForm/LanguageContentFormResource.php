<?php

namespace Modules\Language\Http\Resources\Admin\LanguageContentForm;

use Illuminate\Http\Resources\Json\JsonResource;

class LanguageContentFormResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'usage_type' => $this->usage_type,
            'sequence' => $this->sequence,
            'is_enable' => $this->is_enable,
        ];
    }
}
