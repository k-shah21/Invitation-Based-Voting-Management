<?php

namespace Database\Factories;

use App\Models\Voter;
use App\Models\VotingSession;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class InvitationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'voting_session_id' => VotingSession::factory(),
            'voter_id' => Voter::factory(),
            'token' => Str::random(64),
            'status' => 'pending',
            'expires_at' => now()->addDays(7),
        ];
    }
}
