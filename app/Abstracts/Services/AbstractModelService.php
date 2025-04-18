<?php

namespace App\Abstracts\Services;

use App\Contracts\Services\ModelServiceInterface;

use App\Services\Traits\HasModel;

/**
 * @method \Illuminate\Database\Eloquent\Model|$this create()
 */
abstract class AbstractModelService implements ModelServiceInterface
{
    use HasModel;

    protected static $query;

    public function fill(array $attributes)
    {
        $this->model->fill($attributes);

        return $this;
    }

    public function create(array $attributes)
    {
        return $this->model->query()->create($attributes);
    }

    public function update(array $attributes, array $options = []): bool
    {
        return $this->model->update($attributes, $options);
    }

    public function save(array $options = []): bool
    {
        return $this->model->save($options);
    }

    public function delete(): ?bool
    {
        return $this->model->delete();
    }
}
