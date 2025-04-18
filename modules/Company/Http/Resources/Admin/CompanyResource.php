<?php

namespace Modules\Company\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'name_en' => $this->name_en,
            'opendate' => $this->opendate,
            'invoice' => $this->invoice,
            'vatnumber' => $this->vatnumber,
            'ceo' => $this->ceo,
            'tel' => $this->tel,
            'tel_ext' => $this->tel_ext,
            'tel_service' => $this->tel_service,
            'fax' => $this->fax,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'address_en' => $this->address_en,
            'service_time' => $this->service_time,
            'sequence' => $this->sequence,
            'city' => $this->city ? $this->city->only([
                'id',
                'name',
            ]): null,
            'area' => $this->area ? $this->area->only([
                'id',
                'name',
            ]): null,
        ];
    }
}
