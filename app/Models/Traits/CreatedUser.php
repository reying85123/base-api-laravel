<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Model;

trait CreatedUser
{
    protected $createdUserIdColumn = 'created_user_id';
    protected $createdUserNameColumn = 'created_user_name';

    protected static function bootCreatedUser() {
        static::creating(function ($model) {
            $user = auth()->user();
            [$id, $name] = [null, null];

            if($user instanceof Model){
                [$user->getKeyName() => $id, 'name' => $name] = $user;
            }

            $model->{$model->createdUserIdColumn} = $id;
            $model->{$model->createdUserNameColumn} = $name;
        });
    }
}