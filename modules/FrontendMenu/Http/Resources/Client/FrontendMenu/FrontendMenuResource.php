<?php

namespace Modules\FrontendMenu\Http\Resources\Client\FrontendMenu;

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
            'link' => $this->link,
            "parent" => new FrontendMenuResource($this->parent),
            'is_link_blank' => $this->is_link_blank,
            'type' => $this->type,
            'sequence' => $this->sequence,
        ];
    }
}
