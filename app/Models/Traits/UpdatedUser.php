<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Model;

trait UpdatedUser
{
    protected $updatedUserIdColumn = 'updated_user_id';
    protected $updatedUserNameColumn = 'updated_user_name';

    protected static function bootUpdatedUser()
    {
        static::saving(function ($model) {
            $user = auth()->user();
            [$id, $name] = [null, null];

            if ($user instanceof Model) {
                [$user->getKeyName() => $id, 'name' => $name] = $user;
            }

            $model->{$model->updatedUserIdColumn} = $id;
            $model->{$model->updatedUserNameColumn} = $name;
        });
    }
}
