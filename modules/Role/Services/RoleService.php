<?php

namespace Modules\Role\Services;

use Modules\Role\Models\Role;

use App\Abstracts\Services\AbstractModelService;

/**
 * @method Role getModel()
 * @property Role $model
 */
class RoleService extends AbstractModelService
{
    public function __construct(Role $role)
    {
        $this->setModel($role);
    }

    public function touch()
    {
        return $this->model->touch();
    }
}
