<?php

namespace App\Telegram;

use RuntimeException;

class TelegramException extends RuntimeException
{
    public function __construct(string $message, public readonly array $response = [])
    {
        parent::__construct($message);
    }
}
