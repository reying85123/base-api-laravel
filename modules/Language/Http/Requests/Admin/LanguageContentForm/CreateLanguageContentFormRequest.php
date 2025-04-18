<?php

namespace Modules\Language\Http\Requests\Admin\LanguageContentForm;

use Illuminate\Foundation\Http\FormRequest;

class CreateLanguageContentFormRequest extends FormRequest
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
            'usage_type' => ['required', 'string', 'max:255'],
            'is_enable' => ['required', 'boolean'],
            'sequence' =>  ['required', 'integer'],
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
