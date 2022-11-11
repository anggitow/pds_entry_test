<?php

namespace App\Services;

use App\Models\User;
use Firebase\JWT\JWT;

class JwtService
{
    public function generate(User $user)
    {
        $payload = [
            'Email' => $user->email, // Subject of the token
            'KeyRandom' => bin2hex(random_bytes(16)),
        ];

        // As you can see we are passing `JWT_SECRET` as the second parameter that will 
        // be used to decode the token in the future.
        return JWT::encode($payload, config('app.jwt_secret'), 'HS256');
    }
}
