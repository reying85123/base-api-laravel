<?php

namespace Modules\Company\Services;

use Modules\Company\Models\Company;
use Modules\City\Models\City;
use Modules\Area\Models\Area;

use App\Abstracts\Services\AbstractModelService;

/**
 * @method Company getModel()
 * @property Company $model
 */
class CompanyService extends AbstractModelService
{
    public function __construct(Company $company)
    {
        $this->setModel($company);
    }

    public function associateCity($cityId)
    {
        $model = !!$cityId ? City::findOrFail($cityId) : null;
        $this->model->city()->associate($model);

        return $this;
    }

    public function associateArea($areaId)
    {
        $model = !!$areaId ? Area::findOrFail($areaId) : null;
        $this->model->area()->associate($model);

        return $this;
    }
}
