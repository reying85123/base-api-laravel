<?php

namespace Modules\Role\Http\Resources\Admin\RoleAuth;

use Modules\User\Http\Resources\Admin\UserResource;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleAuthResource extends JsonResource
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
            'users' => UserResource::collection($this->users),
        ];
    }
}
