<?php

return [
    'token'          => env('TELEGRAM_BOT_TOKEN'),
    'base_url'       => env('TELEGRAM_API_URL', 'https://api.telegram.org'),
    'webhook_secret' => env('TELEGRAM_WEBHOOK_SECRET'),
];
