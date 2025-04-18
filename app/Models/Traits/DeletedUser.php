<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Model;

trait DeletedUser
{
    protected $deletedUserIdColumn = 'deleted_user_id';
    protected $deletedUserNameColumn = 'deleted_user_name';

    protected static function bootDeletedUser() {
        static::deleting(function ($model) {
            $user = auth()->user();
            [$id, $name] = [null, null];

            if($user instanceof Model){
                [$user->getKeyName() => $id, 'name' => $name] = $user;
            }

            $model->{$model->deletedUserIdColumn} = $id;
            $model->{$model->deletedUserNameColumn} = $name;

            $model->save();
        });
    }
}