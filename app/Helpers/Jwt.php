<?php
namespace App\Helpers;

use Firebase\JWT\JWT as JwtFirebase;
use App\Models\User;

class Jwt
{
    public static function encodeUser(User $user)
    {
      $expireDays = intval(env('SESSION_EXPIRE_DAYS'));
      $expireSeconds = 84600* $expireDays;

        $payload = [
            'iss' => 'negocio-facil',
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + $expireSeconds,
        ];

        return JwtFirebase::encode($payload, env('JWT_SECRET'));
    }
}
