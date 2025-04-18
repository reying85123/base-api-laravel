<?php

namespace Modules\FrontendMenu\Models;

use App\Models\Traits\QueryOrderBy;
use App\Models\Traits\KeywordTrait;
use App\Models\Traits\UsesUuid;
use Modules\FrontendMenu\Models\Traits\FrontendMenuTrait;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

use Spatie\Permission\Models\Permission;

class FrontendMenu extends Model
{
    use HasFactory, SoftDeletes, QueryOrderBy, KeywordTrait, FrontendMenuTrait, UsesUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'key',
        'type',
        'link',
        'is_link_blank',
        'is_enable',
        'sequence',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_link_blank' => 'bool',
        'is_enable' => 'bool',
    ];

    /**
     * Get the parent that owns the FrontendMenu
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(FrontendMenu::class, 'parent_id');
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'menus_id');
    }

    public function childs()
    {
        return $this->hasMany(FrontendMenu::class, 'parent_id');
    }

    public function frontendMenuItems()
    {
        return $this->hasMany(FrontendMenuItem::class, 'frontend_menu_id');
    }
}
