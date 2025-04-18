<?php

namespace Modules\System\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class SystemLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = $this->user;

        return [
            'created_at' => $this->created_at->toISO8601String(),
            'description' => $this->description,
            'sourceip' => $this->sourceip,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'account' => $user->account,
            ],
        ];
    }
}