<?php

namespace App\Contracts\Services;

use Illuminate\Database\Eloquent\Builder;

interface ModelServiceInterface
{
    /**
     * 填充Model資料
     */
    public function fill(array $attributes);

    /**
     * Model新增
     *
     * @param array $attributes
     */
    public function create(array $attributes);

    /**
     * Model更新
     *
     * @param array $attributes
     * @param array $options
     * @return boolean
     */
    public function update(array $attributes, array $options): bool;

    /**
     * Model儲存
     *
     * @param array $options
     * @return bool
     */
    public function save(array $options): bool;

    /**
     * Model刪除
     *
     * @return bool|null
     */
    public function delete(): ?bool;
}