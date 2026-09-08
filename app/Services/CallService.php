<?php

namespace App\Services;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Models\Call;
use App\Services\LiveKit\AccessToken;
use App\Services\LiveKit\Host;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CallService
{
    public function __construct(
        private readonly AccessToken $tokens,
        private readonly Host $host,
    ) {
    }

    public function create(Application $application): Call
    {
        $call = $application->calls()->create([
            'provider' => 'livekit',
            'status'   => 'pending',
        ]);

        $this->dial($call);

        return $call->refresh();
    }

    public function dial(Call $call): void
    {
        $roomName = "call-{$call->id}";
        $response = Http::withToken($this->tokens->forDial($roomName))
            ->post($this->host->twirp('twirp/livekit.SIP/CreateSIPParticipant'), [
                'sip_trunk_id'         => config('livekit.sip_trunk_id'),
                'sip_call_to'          => $call->application->phone,
                'room_name'            => $roomName,
                'participant_identity' => "application-{$call->application_id}",
                'wait_until_answered'  => true,
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

        $call->application->update([
            'status' => $call->status === 'dialing' ? ApplicationStatus::Calling : ApplicationStatus::Failed,
        ]);
    }
}
