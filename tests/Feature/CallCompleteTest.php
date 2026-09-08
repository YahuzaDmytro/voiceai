<?php

namespace Tests\Feature;

use App\Models\Call;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CallCompleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_creates_lead_and_stores_dialogue(): void
    {
        $call = Call::factory()->create(['status' => 'dialing']);

        $response = $this->withToken('testkey')->postJson("/api/calls/{$call->id}/complete", [
            'first_name' => 'Anna',
            'outcome'    => 'qualified',
            'needs'      => 'Wants a deposit.',
            'next_step'  => 'Manager follow-up',
            'summary'    => 'Interested in a deposit.',
            'transcript' => [
                ['role' => 'assistant', 'body' => 'Hello, this is the bank.'],
                ['role' => 'user', 'body' => 'I want a deposit.'],
            ],
        ]);

        $response->assertOk()->assertJsonPath('ok', true);
        $this->assertNotNull($response->json('lead_id'));

        $application = $call->application->fresh();
        $this->assertSame('converted', $application->status->value);
        $this->assertSame('Anna', $application->first_name);

        $lead = $application->lead;
        $this->assertSame('qualified', $lead->status->value);
        $this->assertSame('Wants a deposit.', $lead->needs);
        $this->assertSame('Manager follow-up', $lead->next_step);
        $this->assertDatabaseHas('messages', [
            'application_id' => $application->id,
            'channel'        => 'call',
            'role'           => 'user',
            'body'           => 'I want a deposit.',
        ]);
    }

    public function test_complete_marks_not_interested_without_lead(): void
    {
        $call = Call::factory()->create();

        $this->withToken('testkey')->postJson("/api/calls/{$call->id}/complete", [
            'outcome' => 'not_interested',
            'summary' => 'Declined.',
        ])->assertOk()->assertJsonPath('lead_id', null);

        $this->assertSame('not_interested', $call->application->fresh()->status->value);
        $this->assertNull($call->application->lead);
    }

    public function test_agent_key_is_required(): void
    {
        $call = Call::factory()->create();

        $this->postJson("/api/calls/{$call->id}/complete", [
            'outcome' => 'qualified',
            'summary' => 'x',
        ])->assertUnauthorized();
    }
}
