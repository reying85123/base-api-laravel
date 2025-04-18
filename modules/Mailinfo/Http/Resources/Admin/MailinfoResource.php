<?php

namespace Modules\Mailinfo\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class MailinfoResource extends JsonResource
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
            'name' => $this->name,
            'subject' => $this->subject,
            'fromname' => $this->fromname,
            'frommail' => $this->frommail,
            'tomail' => $this->tomail,
            'repeatname' => $this->repeatname,
            'repeatmail' => $this->repeatmail,
            'cc' => $this->cc,
            'bcc' => $this->bcc,
            'content_json' => $this->content_json,
            'content' => $this->content,
        ];
    }
}
