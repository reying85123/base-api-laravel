<?php

namespace Modules\Role\Http\Requests\Admin\Role;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
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
            'name' => "required|string|max:255|unique:roles,name,{$this->route('role')},id",
            'guard_name' => 'required|string|max:255',
            'permissions' => 'present|array',
            'permissions.*.id' => 'required|integer|exists:permissions,id',
        ];
    }

    public function attributes()
    {
        return [
            'name' => '群組名稱',
            'permissions' => '權限清單',
            'permissions.*.id' => '權限ID',
        ];
    }
}
