<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyAgentKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $expected = (string) config('livekit.laravel_api_key');

        abort_unless($expected !== '' && hash_equals($expected, (string) $request->bearerToken()), 401);

        return $next($request);
    }
}
