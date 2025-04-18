<?php

namespace Modules\Relationship\Http\Resources\Admin\Relationship;

use Illuminate\Http\Resources\Json\JsonResource;

class RelationshipDetailResource extends JsonResource
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
            'entity' => $this->resolveResource($this->entity),
            'related_entity' => $this->resolveResource($this->relatedEntity),
            'group' => $this->group,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'priority' => $this->priority,
            'remarks' => $this->remarks,
            'direction_type' => $this->direction_type,
        ];
    }

    /**
     *
     * @param  mixed  $modelInstance  模型實例
     * @param  array|null  $fields  需要的字段（可選）
     * @return mixed
     */
    protected function resolveResource($modelInstance, array $fields = null)
    {
        $resource = null;
        switch (true) {
            default:
                return null;
        }
        return $fields && $resource ? $resource->only($fields) : $resource;
    }
}
