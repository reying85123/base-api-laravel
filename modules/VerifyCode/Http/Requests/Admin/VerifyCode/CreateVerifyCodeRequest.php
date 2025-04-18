<?php

namespace Modules\VerifyCode\Http\Requests\Admin\VerifyCode;

use Modules\VerifyCode\Enums\VerifyCodeTypeEnum;

use Illuminate\Foundation\Http\FormRequest;

class CreateVerifyCodeRequest extends FormRequest
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
            'account' => 'sometimes|nullable|exists:member_accounts,account',
            'email' => 'sometimes|nullable|email',
            'phone' => 'sometimes|nullable|string',
            'phone_country' => 'sometimes|nullable|string',
            'member_account.id' => 'sometimes|integer|exists:member_accounts,id',
            'type' => 'required|string|enum_value:' . VerifyCodeTypeEnum::class,
        ];
    }

    public function attributes()
    {
        return [
            'account' => '帳號',
            'email' => '信箱',
            'phone' => '手機',
            'phone_country' => '手機國碼',
            'type' => '類型',
        ];
    }
}
