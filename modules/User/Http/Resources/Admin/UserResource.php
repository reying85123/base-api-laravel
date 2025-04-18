<?php

namespace Modules\User\Http\Resources\Admin;

use Modules\DataAccessRole\Http\Resources\Admin\DataAccessRoleResource;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $company = $this->company;
        $companyJob = $this->companyJob;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'account' => $this->account,
            'email' => $this->email,
            'phone' => $this->phone,
            'is_enable' => $this->is_enable,
            'remark' => $this->remark,
            'role' => $this->roles->count() > 0 ? [
                'id' => $this->roles->first()->id,
                'name' => $this->roles->first()->name,
            ] : null,
            'data_access_roles' => DataAccessRoleResource::collection($this->dataAccessRoles),
            'company' => !!$company ? [
                'id' => $company->id,
                'name' => $company->name,
            ] : null,
            'company_job' => !!$companyJob ? [
                'id' => $companyJob->id,
                'name' => $companyJob->name,
            ] : null,
        ];
    }
}
