<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessTelegramUpdate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TelegramWebhookController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $secret = config('telegram.webhook_secret');

        if (filled($secret) && $request->header('X-Telegram-Bot-Api-Secret-Token') !== $secret) {
            abort(403);
        }

        ProcessTelegramUpdate::dispatch($request->all());

        return response()->noContent();
    }
}
