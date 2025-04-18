<?php

namespace Modules\Language\Http\Requests\Admin\LanguageContentForm;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLanguageContentFormRequest extends FormRequest
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
            'usage_type' => ['sometimes', 'nullable', 'string', 'max:255'],
            'is_enable' => ['sometimes', 'nullable', 'boolean'],
            'sequence' =>  ['sometimes', 'nullable', 'integer'],
        ];
    }

    public function attributes()
    {
        return [
            'name' => '名稱',
            'usage_type' => '使用類別',
            'sequence' => '排序',
            'is_enable' => '是否啟用',
        ];
    }
}
