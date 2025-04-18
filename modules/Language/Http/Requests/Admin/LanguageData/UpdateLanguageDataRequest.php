<?php

namespace Modules\Language\Http\Requests\Admin\LanguageData;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLanguageDataRequest extends FormRequest
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
            'data_key' => ['sometimes', 'nullable', 'string', 'max:255'],
            'value_type' => ['sometimes', 'nullable', 'string', 'max:255'],
            'usage_type' => ['sometimes', 'nullable', 'string', 'max:255'],
            'component' => ['sometimes', 'nullable', 'string', 'max:255'],
            'i18n_key' => ['sometimes', 'nullable', 'string', 'max:255'],
            'label' => ['sometimes', 'nullable', 'string', 'max:255'],
            'required' => ['sometimes', 'nullable', 'boolean'],
            'disable' => ['sometimes', 'nullable', 'boolean'],
            'readonly' => ['sometimes', 'nullable', 'boolean'],
            'xs' => ['sometimes', 'nullable', 'string', 'max:255'],
            'sm' => ['sometimes', 'nullable', 'string', 'max:255'],
            'md' => ['sometimes', 'nullable', 'string', 'max:255'],
            'lg' => ['sometimes', 'nullable', 'string', 'max:255'],
            'xl' => ['sometimes', 'nullable', 'string', 'max:255'],
            'value' => ['sometimes', 'nullable'],
            'placeholder' => ['sometimes', 'nullable', 'string', 'max:255'],
            'locale' => ['sometimes', 'nullable', 'string', 'max:255'],
            'is_show' => ['sometimes', 'nullable', 'boolean'],
            'sequence' =>  ['sometimes', 'integer'],
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
