<?php

namespace Modules\BrowserHistory\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class TrafficChartReportResource extends JsonResource
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
            'date' => $data['date'],
            'traffic_count' => $data['traffic_count'],
            'ip_count' => $data['ip_count'],
        ];
    }
}
