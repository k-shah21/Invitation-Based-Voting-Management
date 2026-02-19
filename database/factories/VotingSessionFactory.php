<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class VotingSessionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'start_date' => now()->subDays(1),
            'end_date' => now()->addDays(7),
            'status' => 'active',
            'created_by' => User::factory(),
        ];
    }
}
