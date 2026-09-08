<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\Call;
use Illuminate\Database\Eloquent\Factories\Factory;

class CallFactory extends Factory
{
    protected $model = Call::class;

    public function definition(): array
    {
        return [
            'application_id' => Application::factory(),
            'provider'       => 'livekit',
            'status'         => 'pending',
        ];
    }
}
