<?php

namespace App\Services;

use App\Enums\ApplicationStatus;
use App\Enums\LeadStatus;
use App\Models\Application;
use App\Models\Call;
use App\Models\Lead;

class CompleteCallService
{
    public function __construct(
        private readonly ConversationService $conversation,
    ) {
    }

    public function fromCall(Call $call, array $payload): Application
    {
        $application = $call->application;
        $outcome     = $payload['outcome'];

        $application->update([
            'first_name' => $payload['first_name'] ?? $application->first_name,
            'last_name'  => $payload['last_name'] ?? $application->last_name,
            'summary'    => $payload['summary'],
            'status'     => $this->applicationStatus($outcome),
        ]);

        $turns = $payload['transcript'] ?? [];

        if ($turns !== []) {
            $this->conversation->importTranscript($application, $call, $turns);
        }

        $call->update([
            'status'     => 'completed',
            'ended_at'   => now(),
            'transcript' => $payload['summary'],
        ]);

        if ($outcome === 'qualified') {
            $this->createLead($application->fresh(), $payload);
        }

        return $application->refresh();
    }

    protected function applicationStatus(string $outcome): ApplicationStatus
    {
        return match ($outcome) {
            'qualified'      => ApplicationStatus::Converted,
            'not_interested' => ApplicationStatus::NotInterested,
            'no_answer'      => ApplicationStatus::NoAnswer,
            'callback'       => ApplicationStatus::Callback,
            default          => ApplicationStatus::Failed,
        };
    }

    protected function createLead(Application $application, array $payload): Lead
    {
        return Lead::query()->updateOrCreate(
            ['application_id' => $application->id],
            [
                'phone'      => $application->phone,
                'first_name' => $application->first_name,
                'last_name'  => $application->last_name,
                'needs'      => $payload['needs'] ?? null,
                'next_step'  => $payload['next_step'] ?? null,
                'status'     => LeadStatus::Qualified,
            ],
        );
    }
}
