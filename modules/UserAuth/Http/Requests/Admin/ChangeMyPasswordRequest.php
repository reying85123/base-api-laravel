<?php

namespace Modules\UserAuth\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ChangeMyPasswordRequest extends FormRequest
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
            'old_password' => [
                'required',
                'string',
                'min:8',
                'max:255',
            ],
            'new_password' => [
                'required',
                'string',
                'min:8',
                'max:255',
                'regex:/^(?=.*\d)(?=.*[a-zA-Z])(?!.* ).{8,}$/i',
            ],
        ];
    }

    public function attributes()
    {
        return [
            'old_password' => '舊密碼',
            'new_password' => '新密碼',
        ];
    }
}
