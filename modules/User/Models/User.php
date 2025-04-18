<?php

namespace Modules\User\Models;

use Modules\UserAuth\Services\UserAuthService;

use Modules\Company\Models\Company;
use Modules\CompanyJob\Models\CompanyJob;
use Modules\DataAccessRole\Models\DataAccessRole;

use App\Models\Traits\KeywordTrait;
use App\Models\Traits\QueryOrderBy;
use App\Models\Traits\RecordSystemLogTrait;
use App\Models\Traits\UsesUuid;
use Modules\DataAccessRole\Models\Traits\HasDataAccessRoleTrait;

use App\Enums\ModelLogTypeEnum;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

use Spatie\Permission\Traits\HasRoles;

use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, SoftDeletes, HasFactory, Notifiable, HasRoles, QueryOrderBy, KeywordTrait, RecordSystemLogTrait, UsesUuid, HasDataAccessRoleTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'remark',
        'is_enable',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_enable' => 'bool',
        'is_superadmin' => 'bool',
    ];

    protected static function boot()
    {
        static::$logType = ModelLogTypeEnum::USER;

        static::addExcludeSystemLogRouteUri([
            'admin/me',
            'admin/me/change_password',
        ]);

        static::addGlobalScope('superadmin', function (Builder $builder) {
            // 若登入者非為開發者帳號，則預設篩選非開發者之人員帳號
            if (UserAuthService::guard()->hasUser()) {
                if (!UserAuthService::toUser()->is_superadmin) {
                    $builder->where('is_superadmin', false);
                }
            }
        });

        parent::boot();
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Get the company that owns the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Get the companyJob that owns the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function companyJob()
    {
        return $this->belongsTo(CompanyJob::class, 'company_job_id');
    }

    public function dataAccessRoles()
    {
        return $this->morphToMany(DataAccessRole::class, 'model', 'model_has_data_access_roles', 'model_id', 'data_access_role_id');
    }
}
