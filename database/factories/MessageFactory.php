<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\Message;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        return [
            'application_id' => Application::factory(),
            'channel'        => 'call',
            'role'           => 'user',
            'body'           => fake()->sentence(),
        ];
    }
}
