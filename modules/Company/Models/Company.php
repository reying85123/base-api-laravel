<?php

namespace Modules\Company\Models;

use Modules\City\Models\City;
use Modules\Area\Models\Area;

use App\Models\Traits\QueryOrderBy;
use App\Models\Traits\KeywordTrait;
use App\Models\Traits\CreatedUser;
use App\Models\Traits\UpdatedUser;
use App\Models\Traits\DeletedUser;
use App\Models\Traits\RecordSystemLogTrait;

use App\Enums\ModelLogTypeEnum;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory,
        SoftDeletes,
        KeywordTrait,
        QueryOrderBy,
        CreatedUser,
        UpdatedUser,
        DeletedUser,
        RecordSystemLogTrait;

    protected $fillable = [
        'name',
        'name_en',
        'opendate',
        'invoice',
        'vatnumber',
        'ceo',
        'tel',
        'tel_ext',
        'tel_service',
        'fax',
        'phone',
        'email',
        'post_code',
        'address',
        'address_en',
        'service_time',
        'sequence',
    ];

    protected static function boot()
    {
        static::$logType = ModelLogTypeEnum::COMPANY;

        parent::boot();
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }

    /**
     * Get the parent that owns the Company
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Company::class, 'parent_id');
    }
}
