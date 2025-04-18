<?php

namespace Modules\User\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|nullable|email',
            'phone' => 'sometimes|nullable|string|max:255',
            'is_enable' => 'sometimes|boolean',
            'remark' => 'sometimes|nullable|string',
            'role.id' => 'required|integer|exists:roles,id',
            'company.id' => 'sometimes|nullable|integer|exists:companies,id',
            'company_job.id' => 'sometimes|nullable|integer|exists:company_jobs,id',
        ];
    }

    public function attributes()
    {
        return [
            'name' => '人員名稱',
            'email' => '電子信箱',
            'phone' => '聯絡電話(手機/分機)',
            'is_enable' => '是否啟用',
            'remark' => '人員備註',
            'role.id' => '權限群組ID',
            'company.id' => '公司ID',
            'company_job.id' => '公司職稱ID',
        ];
    }
}
