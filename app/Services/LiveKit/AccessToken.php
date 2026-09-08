<?php

namespace App\Services\LiveKit;

use Firebase\JWT\JWT;

class AccessToken
{
    public function forDial(string $roomName): string
    {
        $now = now()->timestamp;

        return JWT::encode([
            'iss'   => config('livekit.key'),
            'sub'   => 'laravel-backend',
            'iat'   => $now,
            'nbf'   => $now,
            'exp'   => now()->addSeconds((int) config('livekit.token_ttl'))->timestamp,
            'video' => [
                'roomCreate' => true,
                'roomAdmin'  => true,
                'roomJoin'   => true,
                'room'       => $roomName,
            ],
            'sip' => [
                'admin' => true,
                'call'  => true,
            ],
        ], config('livekit.secret'), 'HS256');
    }
}
