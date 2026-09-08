<?php

namespace App\Console\Commands;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Services\CallService;
use Illuminate\Console\Command;

class CallApplicationCommand extends Command
{
    protected $signature = 'application:call {phone} {--name=}';

    protected $description = 'Create an application if needed and place an outbound SIP call';

    public function handle(CallService $calls): int
    {
        $call = $calls->create($this->application((string) $this->argument('phone')));

        $this->info("Call {$call->id} status={$call->status}");

        return self::SUCCESS;
    }

    protected function application(string $phone): Application
    {
        return Application::query()->firstOrCreate(
            ['phone' => $phone],
            [
                'first_name' => is_string($this->option('name')) ? $this->option('name') : null,
                'status'     => ApplicationStatus::New,
            ],
        );
    }
}
