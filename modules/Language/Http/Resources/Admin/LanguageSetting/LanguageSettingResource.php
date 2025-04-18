<?php

namespace Modules\Language\Http\Resources\Admin\LanguageSetting;

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
            'is_enable' => $this->is_enable,
            'is_client_enable' => $this->is_client_enable,
            'is_admin_enable' => $this->is_admin_enable,
            'is_default' => $this->is_default,
        ];
    }
}
