<?php

namespace Modules\PlatformAttribute\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Enums\PlatformAttributeEnum;

class PlatformAttribute extends Model
{
    use HasFactory;

    protected $table = "platform_attributes";

    protected $primaryKey = 'name';

    public $incrementing = false;

    public $timestamps = false;

    public $fillable = [
        'name',
        'value'
    ];

    public static function get()
    {
        return parent::all()->reduce(function ($now, $param) {
            $platformAttrKey = $param->name;
            $now[$platformAttrKey] = $param->value;
            return $now;
        }, collect([]));
    }

    public static function castValue($value, $type)
    {
        switch ($type) {
            case 'integer':
                $value = (int)$value;
                break;
            case 'boolean':
                $value = parseBoolean($value);
                break;
            case 'array':
            case 'object':
                $value = json_decode($value);
                break;
        }

        return $value;
    }

    public static function parseValuetoString($value)
    {
        switch (gettype($value)) {
            case 'boolean':
                $value = $value ? 1 : 0;
                break;
            case 'array':
            case 'object':
                $value = json_encode($value);
                break;
        }

        return $value;
    }

    /**
     * Get the Value
     *
     * @param  string  $value
     * @return string
     */
    public function getValueAttribute($value)
    {
        return static::castValue($value, PlatformAttributeEnum::getPlatformAttrType($this->attributes['name']));
    }

    /**
     * Set the Value
     *
     * @param  string  $value
     * @return mixed
     */
    public function setValueAttribute($value)
    {
        return static::parseValuetoString($value);
    }
}
