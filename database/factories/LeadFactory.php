<?php

namespace Database\Factories;

use App\Enums\LeadStatus;
use App\Models\Application;
use App\Models\Lead;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeadFactory extends Factory
{
    protected $model = Lead::class;

    public function definition(): array
    {
        return [
            'application_id' => Application::factory(),
            'phone'          => '+38050' . fake()->numerify('#######'),
            'first_name'     => fake()->firstName(),
            'last_name'      => fake()->optional()->lastName(),
            'needs'          => fake()->sentence(),
            'status'         => LeadStatus::Qualified,
        ];
    }
}
