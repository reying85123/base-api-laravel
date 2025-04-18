<?php

namespace Modules\Company\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateComapnyRequest extends FormRequest
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
            'name' => 'sometimes|nullable|string|max:255',
            'name_en' => 'sometimes|nullable|string|max:255',
            'opendate' => 'sometimes|nullable|string|max:255',
            'invoice' => 'sometimes|nullable|string|max:255',
            'vatnumber' => 'sometimes|nullable|string|max:255',
            'ceo' => 'sometimes|nullable|string|max:255',
            'tel' => 'sometimes|nullable|string|max:255',
            'tel_ext' => 'sometimes|nullable|string|max:255',
            'tel_service' => 'sometimes|nullable|string|max:255',
            'fax' => 'sometimes|nullable|string|max:255',
            'phone' => 'sometimes|nullable|string|max:255',
            'email' => 'sometimes|nullable|email|max:255',
            'post_code' => 'sometimes|nullable|string|max:255',
            'address' => 'sometimes|nullable|string|max:255',
            'address_en' => 'sometimes|nullable|string|max:255',
            'service_time' => 'sometimes|nullable|string|max:255',
            'sequence' => 'sometimes|nullable|integer|max:255',
            'city.id' => 'sometimes|nullable|integer|exists:commons,id,deleted_at,NULL,common_group,Address,parent_id,NULL',
            'area.id' => 'sometimes|nullable|integer|exists:commons,id,deleted_at,NULL,common_group,Address,parent_id,' . $this->input('city.id', -1),
        ];
    }

    public function attributes()
    {
        return [
            'name' => '公司名稱',
            'name_en' => '公司名稱(英)',
            'opendate' => '成立時間',
            'invoice' => '發票抬頭',
            'vatnumber' => '統一編號',
            'ceo' => '負責人',
            'tel' => '主要電話',
            'tel_ext' => '分機',
            'tel_service' => '客服專線',
            'fax' => '傳真',
            'phone' => '手機',
            'email' => '連絡信箱',
            'post_code' => '郵遞區號',
            'address' => '地址',
            'address_en' => '地址(英)',
            'service_time' => '客服時間',
            'sequence' => '排序',
            'city.id' => '縣市ID',
            'area.id' => '行政區ID',
        ];
    }
}
