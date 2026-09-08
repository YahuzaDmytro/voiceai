<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Services\CallService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CallServiceDialTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_dials_livekit_sip(): void
    {
        Http::fake([
            'https://livekit.test/*' => Http::response([
                'sip_call_id' => 'sip_1',
            ], 200),
        ]);

        $application = Application::factory()->create(['phone' => '+380501234567']);
        $call        = app(CallService::class)->create($application);

        $this->assertSame('dialing', $call->status);
        $this->assertSame('sip_1', $call->external_id);
        $this->assertSame('calling', $application->fresh()->status->value);

        Http::assertSent(function ($request) use ($application, $call) {
            return str_contains($request->url(), 'CreateSIPParticipant')
                && $request['sip_call_to'] === $application->phone
                && $request['room_name'] === 'call-' . $call->id
                && $request['sip_trunk_id'] === 'ST_test';
        });
    }

    public function test_failed_dial_marks_call_failed(): void
    {
        Http::fake([
            'https://livekit.test/*' => Http::response(['error' => 'no trunk'], 400),
        ]);

        $application = Application::factory()->create();
        $call        = app(CallService::class)->create($application);

        $this->assertSame('failed', $call->status);
        $this->assertSame('failed', $application->fresh()->status->value);
    }
}
