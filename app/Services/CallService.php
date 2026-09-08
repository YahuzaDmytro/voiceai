<?php

namespace App\Services;

use App\Models\Call;
use App\Models\Lead;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CallService
{
    public function create(Lead $lead): Call
    {
        return $lead->calls()->create([
            'provider' => 'livekit',
            'status'   => 'pending',
        ]);
    }

    protected function generateToken(string $roomName): string
    {
        $payload = [
            'iss'   => config('livekit.key'),
            'sub'   => 'laravel-backend',
            'iat'   => now()->timestamp,
            'nbf'   => now()->timestamp,
            'exp'   => now()->addSeconds(config('livekit.token_ttl'))->timestamp,
            'video' => [
                'roomAdmin' => true,
                'room'      => $roomName,
            ],
        ];

        return JWT::encode($payload, config('livekit.secret'), 'HS256');
    }

    protected function dial(Call $call): void
    {
        $roomName = "call-{$call->id}";
        $token    = $this->generateToken($roomName);

        $response = Http::withToken($token)
            ->post(config('livekit.host') . '/twirp/livekit.SIP/CreateSIPParticipant', [
                'sip_trunk_id'         => config('livekit.sip_trunk_id'),
                'sip_call_to'          => $call->lead->phone,
                'room_name'            => $roomName,
                'participant_identity' => "lead-{$call->lead_id}",
                'wait_until_answered'  => false,
            ]);

        if ($response->failed()) {
            Log::warning('LiveKit dial failed', [
                'call_id' => $call->id,
                'status'  => $response->status(),
                'body'    => $response->body(),
            ]);
        }

        $call->update([
            'status'      => $response->successful() ? 'dialing' : 'failed',
            'external_id' => $response->json('sip_call_id') ?? $response->json('participant_id'),
            'started_at'  => now(),
            'metadata'    => $response->json() ?? ['error' => $response->body()],
        ]);
    }
}
