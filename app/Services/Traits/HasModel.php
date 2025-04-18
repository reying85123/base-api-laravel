<?php

namespace App\Services\Traits;

use Illuminate\Database\Eloquent\Model;

/**
 * Service Model
 * @property Model $staticModel
 */
trait HasModel
{
    protected $model;

    public function setModel(Model $model)
    {
        $this->model = $model;
    }

    public function getModel()
    {
        return $this->model;
    }
}
