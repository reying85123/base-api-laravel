<?php

namespace Modules\VerifyCode\Http\Resources\Admin\VerifyCode;

use Modules\VerifyCode\Enums\VerifyCodeTypeEnum;

use Illuminate\Http\Resources\Json\JsonResource;

class VerifyCodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $verifyCodeTypeOptions = VerifyCodeTypeEnum::toSelectArray();

        return [
            'id' => $this->id,
            'created_at' => $this->created_at->toISO8601String(),
            'updated_at' => $this->updated_at->toISO8601String(),
            'account' => $this->account,
            'token' => $this->token,
            'email' => $this->email,
            'phone' => $this->phone,
            'phone_country' => $this->phone_country,
            'type' => $this->type,
            'type_text' => $verifyCodeTypeOptions[$this->type] ?? null,
            'ip' => $this->ip,
        ];
    }
}
