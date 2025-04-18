<?php

namespace Modules\System\Http\Resources\Client;

use Illuminate\Http\Resources\Json\JsonResource;

class ViewcountResource extends JsonResource
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
            'value' => $this['count'],
        ];
    }
}
