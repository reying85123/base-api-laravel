<?php

namespace App\Services;

use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService
{
    public static function generateToken($sub, $exp = 5, $contact = null)
    {
        $datetimeNow = getDateTime(null, true);
        $tokenId = base64_encode(random_bytes(32));

        $data = [
            'iss' => request()->url(),
            'sub' => $sub,
            'nbf' => $datetimeNow->timestamp,
            'iat' => $datetimeNow->timestamp,
            'exp' => $datetimeNow->addMinutes($exp)->timestamp,
            'jti' => $tokenId,
            "contact" => $contact,
        ];

        return self::encodeJWT($data);
    }

    public static function decodeJWT($token)
    {
        try {
            $data = JWT::decode($token, new Key(config('jwt.secret'), 'HS256'));
            return $data;
        } catch (ExpiredException $e) {
            abort(401, '操作已過期失效，請重新操作');
        } catch (\Exception $e) {
            abort(400, $e->getMessage());
        }
    }

    public static function encodeJWT($data)
    {
        return JWT::encode($data, config('jwt.secret'), 'HS256');
    }
}
