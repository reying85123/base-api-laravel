<?php

namespace Modules\FrontendMenu\Http\Requests\Admin\FrontendMenu;

use Modules\FrontendMenu\Enums\FrontendMenuTypeEnum;

use Illuminate\Foundation\Http\FormRequest;

class CreateFrontendMenuRequest extends FormRequest
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
            'parent.id' => 'sometimes|nullable|string|exists:frontend_menus,id',
            'link' => 'sometimes|nullable|string',
            'is_link_blank' => 'required|boolean',
            'is_enable' => 'required|boolean',
            'type' => 'sometimes|nullable|string|enum_value:' . FrontendMenuTypeEnum::class,
            'sequence' => 'sometimes|integer',
        ];
    }

    public function attributes()
    {
        return [
            'name' => '前端選單名稱',
        ];
    }
}
