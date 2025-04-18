<?php

namespace Modules\DataAccessRole\Services;

use Modules\DataAccessRole\Models\DataAccessRole;

use App\Abstracts\Services\AbstractModelService;

/**
 * @method DataAccessRole getModel()
 * @property DataAccessRole $model
 */
class DataAccessRoleService extends AbstractModelService
{
    public function __construct(DataAccessRole $model)
    {
        $this->setModel($model);
    }
}
