<?php

namespace Modules\Language\Http\Resources\Admin\LanguageContentForm;

use Modules\Language\Http\Resources\Admin\LanguageContentFormField\LanguageContentFormFieldResource;

use Illuminate\Http\Resources\Json\JsonResource;

class LanguageContentFormDetailResource extends JsonResource
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
            'fields' => LanguageContentFormFieldResource::collection($this->languageContentFormFields),
        ];
    }
}
