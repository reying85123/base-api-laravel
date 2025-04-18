<?php

namespace Modules\FrontendMenu\Http\Resources\Admin\FrontendMenu;

use Illuminate\Http\Resources\Json\JsonResource;

class FrontendMenuDetailResource extends JsonResource
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
            'link' => $this->link,
            "parent" => new FrontendMenuDetailResource($this->parent),
            'is_link_blank' => $this->is_link_blank,
            'is_enable' => $this->is_enable,
            'type' => $this->type,
            'items' => $this->frontendMenuItems->map(function ($item) {
                return collect($item->model)->only(['id', 'name', 'title']);
            }),
            'sequence' => $this->sequence,
        ];
    }
}
