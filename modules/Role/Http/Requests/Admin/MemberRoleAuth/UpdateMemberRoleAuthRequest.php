<?php

namespace Modules\Role\Http\Requests\Admin\MemberRoleAuth;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMemberRoleAuthRequest extends FormRequest
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
            'members' => 'present|array',
            'members.*.id' => 'required|integer|exists:member_accounts,id',
        ];
    }

    public function attributes()
    {
        return [
            'members' => '會員清單',
            'members.*.id' => '會員ID',
        ];
    }
}
