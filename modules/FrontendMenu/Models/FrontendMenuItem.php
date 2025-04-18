<?php

namespace Modules\FrontendMenu\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FrontendMenuItem extends Model
{
    use HasFactory;

    protected $table = 'model_has_frontend_menus';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'frontend_menu_id',
        'model_type',
        'model_id',
    ];

    public $timestamps = false;

    public function frontendMenu()
    {
        return $this->belongsTo(FrontendMenu::class, 'frontend_menu_id');
    }

    public function model()
    {
        return $this->morphTo();
    }
}
