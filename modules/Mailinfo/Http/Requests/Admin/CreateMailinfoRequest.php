<?php

namespace Modules\Mailinfo\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CreateMailinfoRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'web_name' => 'required|string|max:255',
            'siteurl' => 'required|url|max:255',
            'fromname' => 'required|string|max:255',
            'frommail' => 'required|email|max:255',
            'tomail' => 'required|email|max:255',
            'repeatname' => 'exclude_unless:isrepeat,true|required|string|max:255',
            'repeatmail' => [
                'exclude_unless:isrepeat,true',
                'required',
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
            'ccmail' => [
                'exclude_unless:isbcc,true',
                'required',
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
            'isbcc' => 'required|boolean',
            'isrepeat' => 'required|boolean',
            'content' => 'sometimes|string',
            'company.id' => 'required|integer|exists:companies,id,deleted_at,NULL',
        ];
    }

    public function attributes()
    {
        return [
            'name' => '信件名稱',
            'subject' => '信件主旨',
            'web_name' => '信件網址名稱',
            'siteurl' => '信件網址',
            'fromname' => '寄件者名稱',
            'frommail' => '寄件者信箱',
            'tomail' => '收件者信箱',
            'repeatname' => '回覆者名稱',
            'repeatmail' => '回覆者信箱',
            'ccmail' => '副本信箱',
            'isbcc' => '是否密件回函',
            'isrepeat' => '是否接受回覆',
            'content' => '信件內容(HTML)',
            'company.id' => '公司ID',
        ];
    }
}
