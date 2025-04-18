<?php

namespace App\Services;

use App\Models\Base\Key;

use Illuminate\Support\Str;

class KeyService
{
    public static function generateKey($prefix = 'PK', $digits = 10)
    {
        $code = Str::random($digits);
        $key = new Key([
            'prefix' => $prefix,
            'code' => $code,
            'value' => $prefix . $code,
        ]);
        $key->save();
        return $key;
    }
}
