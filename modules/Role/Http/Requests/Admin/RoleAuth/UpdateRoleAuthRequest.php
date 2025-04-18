<?php

namespace Modules\Role\Http\Requests\Admin\RoleAuth;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleAuthRequest extends FormRequest
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
            'users' => 'present|array',
            'users.*.id' => 'required|uuid|exists:users,id',
        ];
    }

    public function attributes()
    {
        return [
            'users' => '人員清單',
            'users.*.id' => '人員ID',
        ];
    }
}
