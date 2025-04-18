<?php

namespace Modules\Role\Models;

use Modules\UserAuth\Services\UserAuthService;

use Modules\Menu\Models\Menu;

use App\Models\Traits\KeywordTrait;
use App\Models\Traits\QueryOrderBy;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Role as PermissionRole;

class MemberRole extends PermissionRole
{
    use HasFactory, KeywordTrait, QueryOrderBy;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'guard_name',
    ];

    public function getMenusAttribute()
    {
        return cache()->remember('role_menus', now()->endOfDay(), function () {
            return Menu::with('permissions')->orderBy('sequence')->get();
        });
    }

    /**
     * A role belongs to some members of the model associated with its guard.
     */
    public function members()
    {
        $this->guard_name = 'client_api';
        return $this->morphedByMany(
            getModelForGuard($this->attributes['guard_name'] ?? config('auth.defaults.guard')),
            'model',
            config('permission.table_names.model_has_roles'),
            PermissionRegistrar::$pivotRole,
            config('permission.column_names.model_morph_key')
        );
    }

    protected static function boot()
    {
        static::addGlobalScope('superadmin', function (Builder $builder) {
            // 若登入者非為開發者帳號，則預設篩選非開發者之群組
            if (UserAuthService::guard()->hasUser()) {
                if (!UserAuthService::toUser()->is_superadmin) {
                    $builder->where('is_superadmin', false);
                }
            }
        });

        parent::boot();
    }
}
