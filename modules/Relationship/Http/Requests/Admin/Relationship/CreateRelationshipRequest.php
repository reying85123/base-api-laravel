<?php

namespace Modules\Relationship\Http\Requests\Admin\Relationship;

use Illuminate\Foundation\Http\FormRequest;

class CreateRelationshipRequest extends FormRequest
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
            'entity.id' => 'required',
            'entity.type' => 'required|string',
            'related_entity.id' => 'required',
            'related_entity.type' => 'required|string',
            'group' => 'required|string',
            'start_date' => 'sometimes|nullable|date',
            'end_date' => 'sometimes|nullable|date',
            'priority' => 'sometimes|nullable|in:high,medium,low',
            'remarks' => 'sometimes|nullable|string',
            'direction_type' => 'required|in:single,mutual',
        ];
    }

    public function attributes()
    {
        return [
            'entity.id' => '實體 ID',
            'entity.type' => '實體類型',
            'related_entity.id' => '關聯實體 ID',
            'related_entity.type' => '關聯實體類型',
            'group' => '群組',
            'start_date' => '開始日期',
            'end_date' => '結束日期',
            'priority' => '優先順序',
            'remarks' => '備註',
            'direction_type' => '方向類型',
        ];
    }
}
