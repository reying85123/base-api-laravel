<?php

namespace Modules\DataAccessRole\Models\Traits;

use Modules\DataAccessRole\Models\DataAccessRole;

trait HasDataAccessRoleTrait
{

    /**
     * Get the user that owns the HasDataAccessRoleTrait
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dataAccessRole()
    {
        return $this->morphToMany(DataAccessRole::class, 'model', 'model_has_data_access_roles', 'model_id', 'data_access_role_id');
    }
}
