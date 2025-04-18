<?php

namespace Modules\UserAuth\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'account' => $this->account,
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => [
                'id' => $this->roles->first()->id,
                'name' => $this->roles->first()->name,
            ]
        ];
    }
}
