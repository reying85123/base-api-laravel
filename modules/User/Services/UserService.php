<?php

namespace Modules\User\Services;

use Modules\User\Models\User;
use Modules\Company\Models\Company;
use Modules\CompanyJob\Models\CompanyJob;

use App\Abstracts\Services\AbstractModelService;

use Illuminate\Support\Facades\Hash;

/**
 * @method User getModel()
 * @property User $model
 */
class UserService extends AbstractModelService
{
    public function __construct(User $model)
    {
        $this->setModel($model);
    }

    public function setAccount(string $account)
    {
        $this->model->account = $account;

        return $this;
    }

    public function setPassword(string $password, $needHash = true)
    {
        $this->model->password = $needHash ? Hash::make($password) : $password;

        return $this;
    }

    /**
     * 關聯公司資訊
     *
     * @param null|Company $model
     */
    public function associateCompany($companyId)
    {
        $model = !!$companyId ? Company::findOrFail($companyId) : null;
        $this->model->company()->associate($model);

        return $this;
    }

    /**
     * 關聯公司職稱資訊
     *
     * @param null|CompanyJob $model
     */
    public function associateCompanyJob($companyJobId)
    {
        $model = !!$companyJobId ? CompanyJob::findOrFail($companyJobId) : null;
        $this->model->companyJob()->associate($model);

        return $this;
    }

    public function syncDataAccessRoles(array $dataAccessRoles)
    {
        $this->model->dataAccessRoles()->sync($dataAccessRoles);

        return $this;
    }
}
