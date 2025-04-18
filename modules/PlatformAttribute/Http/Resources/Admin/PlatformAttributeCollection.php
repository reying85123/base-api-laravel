<?php

namespace Modules\PlatformAttribute\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PlatformAttributeCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->mapWithKeys(function($platformAttribute){
            return [$platformAttribute->name => $platformAttribute->value];
        })->toArray();
    }
}
