<?php

namespace Modules\CompanyJob\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyJobRequest extends FormRequest
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
            'name' => 'sometimes|string|max:255',
            'parent_job' => 'sometimes|nullable',
            'parent_job.id' => 'required_unless:parent_job,null|integer|exists:company_jobs,id,deleted_at,NULL',
            'sequence' => 'sometimes|integer',
        ];
    }

    public function attributes()
    {
        return [
            'name' => '職稱名稱',
            'parent_job' => '上級職稱',
            'parent_job.id' => '上級職稱ID',
            'sequence' => '排序',
        ];
    }
}
