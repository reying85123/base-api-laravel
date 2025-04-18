<?php

namespace Modules\Language\Http\Requests\Admin\LanguageSetting;

use Illuminate\Foundation\Http\FormRequest;

class CreateLanguageSettingRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'locale' => ['required', 'string'],
            'is_enable' => ['required', 'boolean'],
            'sequence' =>  ['required', 'integer'],
        ];
    }

    public function attributes()
    {
        return [
            'name' => '名稱',
            'locale' => '語系',
            'sequence' => '排序',
            'is_enable' => '是否啟用',
        ];
    }
}
