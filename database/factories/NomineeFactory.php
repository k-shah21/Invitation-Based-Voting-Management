<?php

namespace Database\Factories;

use App\Models\VotingSession;
use Illuminate\Database\Eloquent\Factories\Factory;

class NomineeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'voting_session_id' => VotingSession::factory(),
            'name' => $this->faker->name,
            'designation' => $this->faker->jobTitle,
            'country' => $this->faker->country,
            'city' => $this->faker->city,
            'image' => null,
        ];
    }
}
