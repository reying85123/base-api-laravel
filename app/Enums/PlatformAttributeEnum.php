<?php

namespace App\Enums;

use Jiannei\Enum\Laravel\Enum;

class PlatformAttributeEnum extends Enum
{
    const EXAMPLE = 'example';

    public static function getPlatformAttrType($platformAttrKey)
    {
        $platformAttrType = [
            'example' => 'string',
        ];

        return $platformAttrType[$platformAttrKey] ?? null;
    }

    public static function getPlatformAttrDescription($platformAttrKey)
    {
        $platformAttrDescription = [
            'example' => '範例',
        ];

        return $platformAttrDescription[$platformAttrKey] ?? null;
    }

    public static function getDefaultValue($platformAttrVal)
    {
        $default_value = "";

        switch (static::getPlatformAttrType($platformAttrVal)) {
            case 'string':
                $default_value = "";
                break;
            case 'integer':
                $default_value = 0;
                break;
            case 'array':
                $default_value = '[]';
                break;
            case 'object':
                $default_value = '{}';
                break;
            case 'boolean':
                $default_value = 0;
                break;
            default:
                $default_value = "";
                break;
        }

        return $default_value;
    }
}
