<?php

return [

    'host' => env('LIVEKIT_HOST'),

    'key' => env('LIVEKIT_API_KEY'),

    'secret' => env('LIVEKIT_API_SECRET'),

    'sip_trunk_id' => env('LIVEKIT_SIP_TRUNK_ID'),

    'token_ttl' => env('LIVEKIT_TOKEN_TTL', 600),

    'laravel_api_key' => env('LEAD_CALLER_API_KEY'),

];
