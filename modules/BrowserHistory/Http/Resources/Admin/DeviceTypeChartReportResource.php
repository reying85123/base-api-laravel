<?php

namespace Modules\BrowserHistory\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class DeviceTypeChartReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = $this->resource;
        return [
            'device_type' => $data['device_type'],
            'count' => $data['count'],
        ];
    }
}
