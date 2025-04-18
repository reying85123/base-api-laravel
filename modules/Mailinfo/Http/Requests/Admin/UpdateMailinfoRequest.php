<?php

namespace Modules\Mailinfo\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMailinfoRequest extends FormRequest
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
            'subject' => 'sometimes|string|max:255',
            'fromname' => 'sometimes|nullable|string|max:255',
            'frommail' => 'sometimes|email|max:255',
            'repeatname' => 'required_with:repeatmail|sometimes|nullable|max:255',
            'repeatmail' => 'required_with:repeatname|sometimes|nullable|email|max:255',
            'tomail' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $email_list = explode(',', $value);

                    foreach ($email_list as $email) {
                        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $fail(':attribute 信箱格式不符合');
                        }
                    }
                },
            ],
            'cc' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $email_list = explode(',', $value);

                    foreach ($email_list as $email) {
                        if (!filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
                            $fail(':attribute 信箱格式不符合');
                        }
                    }
                },
            ],
            'bcc' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $email_list = explode(',', $value);

                    foreach ($email_list as $email) {
                        if (!filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
                            $fail(':attribute 信箱格式不符合');
                        }
                    }
                },
            ],
            'content_json' => 'sometimes|nullable|string',
            'content' => 'sometimes|nullable|string',
        ];
    }

    public function attributes()
    {
        return [
            'name' => '信件名稱',
            'subject' => '信件主旨',
            'fromname' => '寄件者名稱',
            'frommail' => '寄件者信箱',
            'tomail' => '收件者信箱',
            'repeatname' => '回覆者名稱',
            'repeatmail' => '回覆者信箱',
            'cc' => '副本信箱',
            'bcc' => '密件副本信箱',
            'content_json' => '信件內容(JSON)',
            'content' => '信件內容(HTML)',
        ];
    }
}
