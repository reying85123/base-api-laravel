<?php

namespace Modules\UserAuth\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class TokenResource extends JsonResource
{

    protected $token;

    public function __construct(\PHPOpenSourceSaver\JWTAuth\Payload $resource, $token)
    {
        parent::__construct($resource);
        $this->token = $token;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'token' => $this->token,
            'iat' => $this->get('iat'),
            'exp' => $this->get('exp'),
        ];
    }
}
