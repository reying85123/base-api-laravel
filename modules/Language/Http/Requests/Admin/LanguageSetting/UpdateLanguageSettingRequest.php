<?php

namespace Modules\Language\Http\Requests\Admin\LanguageSetting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLanguageSettingRequest extends FormRequest
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
            'name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'locale' => ['sometimes', 'nullable', 'string'],
            'is_enable' => ['sometimes', 'nullable', 'boolean'],
            'sequence' =>  ['sometimes', 'nullable', 'integer'],
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
