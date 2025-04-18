<?php

namespace Modules\MailLog\Http\Resources\Admin;

use App\Enums\MailLogStateEnum;

use Illuminate\Http\Resources\Json\JsonResource;

class MailLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $mailLogState = MailLogStateEnum::toSelectArray();

        return [
            'id' => $this->id,
            'from' => $this->from,
            'to' => $this->to,
            'cc' => $this->cc,
            'bcc' => $this->bcc,
            'reply_to' => $this->reply_to,
            'subject' => $this->subject,
            'send_datetime' => $this->send_datetime->toISO8601String(),
            'content' => $this->content,
            'state' => $this->state,
            'state_text' => $mailLogState[$this->state],
        ];
    }
}
