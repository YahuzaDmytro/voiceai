<?php

namespace App\Services\LiveKit;

class Host
{
    public function twirp(string $path): string
    {
        $host = (string) config('livekit.host');
        $host = (string) preg_replace('#^wss://#', 'https://', $host);
        $host = (string) preg_replace('#^ws://#', 'http://', $host);

        return rtrim($host, '/') . '/' . ltrim($path, '/');
    }
}
