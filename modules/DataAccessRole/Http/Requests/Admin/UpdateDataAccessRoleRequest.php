<?php

namespace Modules\DataAccessRole\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDataAccessRoleRequest extends FormRequest
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
        ];
    }

    public function attributes()
    {
        return [
            'name' => '資料權限群組名稱',
        ];
    }
}
