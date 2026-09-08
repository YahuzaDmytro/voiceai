<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Call;
use App\Models\Message;

class ConversationService
{
    public function add(Application $application, string $channel, string $role, string $body, ?Call $call = null): Message
    {
        return $application->messages()->create([
            'call_id' => $call?->id,
            'channel' => $channel,
            'role'    => $role,
            'body'    => $body,
        ]);
    }

    public function importTranscript(Application $application, Call $call, array $turns): void
    {
        foreach ($turns as $turn) {
            $this->add($application, 'call', $turn['role'], $turn['body'], $call);
        }
    }

    public function transcriptText(Application $application): string
    {
        return $application->messages()
            ->orderBy('id')
            ->get()
            ->map(fn (Message $message) => strtoupper($message->channel) . ' ' . $message->role . ': ' . $message->body)
            ->implode("\n");
    }
}
