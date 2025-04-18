<?php

namespace App\Http\Resources\Base\System;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryCodeResource extends JsonResource
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
            'country_code' => $this['country_code'],
            'country_name' => $this['country_name'],
            'country_phone_code' => $this['country_phone_code'],
            'country_icon' => $this['country_icon'],
        ];
    }
}
