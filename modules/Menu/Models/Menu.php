<?php

namespace Modules\Menu\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Permission\Models\Permission;

class Menu extends Model
{
    use HasFactory;
    use \App\Models\Traits\UsesUuid;

    protected static function booted(){
        static::addGlobalScope('sortBySequence', function(Builder $builder){
            $builder->orderBy('sequence');
        });
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'menus_id');
    }

    public function childs()
    {
        return $this->hasMany(Menu::class, 'parent_id');
    }
}
