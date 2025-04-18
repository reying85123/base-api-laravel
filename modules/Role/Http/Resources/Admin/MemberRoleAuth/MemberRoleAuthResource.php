<?php

namespace Modules\Role\Http\Resources\Admin\MemberRoleAuth;

use Modules\Member\Http\Resources\Admin\MemberAccount\MemberAccountResource;

use Illuminate\Http\Resources\Json\JsonResource;

class MemberRoleAuthResource extends JsonResource
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
            'members' => $this->members,
        ];
    }
}
