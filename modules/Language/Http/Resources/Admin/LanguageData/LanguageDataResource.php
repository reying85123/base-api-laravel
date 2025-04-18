<?php

namespace Modules\Language\Http\Resources\Admin\LanguageData;

use Illuminate\Http\Resources\Json\JsonResource;

class LanguageDataResource extends JsonResource
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
            'data_key' => $this->data_key,
            'value_type' => $this->value_type,
            'usage_type' => $this->usage_type,
            'component' => $this->component,
            'i18n_key' => $this->i18n_key,
            'label' => $this->label,
            'required' => $this->required,
            'disable' => $this->disable,
            'readonly' => $this->readonly,
            'is_show' => $this->is_show,
            'layout' => [
                'xs' => $this->xs,
                'sm' => $this->sm,
                'md' => $this->md,
                'lg' => $this->lg,
                'xl' => $this->xl,
            ],
            'locale' => $this->locale,
            'value' => $this->value,
            'placeholder' => $this->placeholder,
            'sequence' => $this->sequence,
        ];
    }
}
