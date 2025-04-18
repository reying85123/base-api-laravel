<?php

namespace Modules\BrowserHistory\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class BrowserHistoryResource extends JsonResource
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
            'device_type' => $this->device_type,
            'platform' => $this->platform,
            'sourceip' => $this->sourceip,
            'browser' => $this->browser,
            'is_robot' => $this->is_robot,
            'link' => $this->link,
        ];
    }
}
