<?php

namespace Modules\Language\Http\Resources\Client\LanguageSetting;

use Illuminate\Http\Resources\Json\JsonResource;

class LanguageSettingResource extends JsonResource
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
            'locale' => $this->locale,
            'sequence' => $this->sequence,
            'is_default' => $this->is_default,
        ];
    }
}
