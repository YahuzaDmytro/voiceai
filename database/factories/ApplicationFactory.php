<?php

namespace Database\Factories;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationFactory extends Factory
{
    protected $model = Application::class;

    public function definition(): array
    {
        return [
            'phone'      => '+38050' . fake()->numerify('#######'),
            'first_name' => fake()->optional()->firstName(),
            'last_name'  => fake()->optional()->lastName(),
            'status'     => ApplicationStatus::New,
        ];
    }
}
