<?php

namespace Modules\BrowserHistory\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class BrowserChartReportResource extends JsonResource
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
            'browser' => $data['browser'],
            'count' => $data['count'],
        ];
    }
}
