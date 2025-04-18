<?php

namespace Modules\FrontendMenu\Http\Resources\Admin\FrontendMenu;

use Modules\FrontendMenu\Http\Resources\Admin\FrontendMenu\FrontendMenuDetailResource;

use Illuminate\Http\Resources\Json\JsonResource;

class FrontendMenuResource extends JsonResource
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
            'created_at' => $this->created_at->toISO8601String(),
            'updated_at' => $this->updated_at->toISO8601String(),
            'name' => $this->name,
            "parent" => $this->parent !== null ? (new FrontendMenuDetailResource($this->parent))->only(['id', 'name']) : null,
            'type' => $this->type,
            'is_enable' => $this->is_enable,
            'sequence' => $this->sequence,
        ];
    }
}
