<?php

namespace Modules\CompanyJob\Services\CompanyJob;

use Modules\CompanyJob\Models\CompanyJob;

use App\Exceptions\Services\ModelServiceDataException;

use App\Abstracts\Services\AbstractModelService;

/**
 * @method CompanyJob getModel()
 * @property CompanyJob $model
 */
class CompanyJobService extends AbstractModelService
{
    public function __construct(CompanyJob $companyJob)
    {
        $this->setModel($companyJob);
    }

    public function associateCompanyJob(?CompanyJob $model)
    {
        if($model !== null && !$this->model->checkParentId($model->id)){
            throw new ModelServiceDataException('父類別不可任一子類別');
        }

        if($model !== null && $this->model->exists && $this->model->id === $model->id){
            throw new ModelServiceDataException('父類別不可為自身');
        }

        $this->model->parentJob()->associate($model);

        return $this;
    }

    public static function unbindChildCompanyJob(CompanyJob $parentCompanyJob)
    {
        return CompanyJob::query()
            ->where('parent_id', $parentCompanyJob->id)
            ->update([
                'parent_id' => null,
            ]);
    }
}
