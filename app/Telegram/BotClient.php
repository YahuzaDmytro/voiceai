<?php

namespace App\Telegram;

use Illuminate\Support\Facades\Http;

class BotClient
{
    public function __construct(
        private readonly string $token,
        private readonly string $baseUrl = 'https://api.telegram.org',
    ) {
    }

    public function sendMessage(int|string $chatId, string $text, array $params = []): array
    {
        return $this->call('sendMessage', [
            'chat_id' => $chatId,
            'text'    => $text,
            ...$params,
        ]);
    }

    public function answerCallbackQuery(string $callbackQueryId, array $params = []): array
    {
        return $this->call('answerCallbackQuery', [
            'callback_query_id' => $callbackQueryId,
            ...$params,
        ]);
    }

    public function call(string $method, array $params = []): array
    {
        $payload = Http::baseUrl(rtrim($this->baseUrl, '/') . '/bot' . $this->token . '/')
            ->acceptJson()
            ->asJson()
            ->timeout(10)
            ->post($method, $params)
            ->throw()
            ->json();

        if (! ($payload['ok'] ?? false)) {
            throw new TelegramException(
                $payload['description'] ?? 'Telegram API error',
                is_array($payload) ? $payload : [],
            );
        }

        $result = $payload['result'] ?? [];

        return $result;
    }
}
