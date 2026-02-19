<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Voter;
use App\Models\VotingSession;
use App\Models\Nominee;
use App\Models\Invitation;
use App\Models\Vote;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VotingSystemSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Admin',
                'password' => bcrypt('password'),
            ]
        );

        // 2. Create an Active Session
        $session = VotingSession::create([
            'title' => 'Annual General Election 2026',
            'description' => 'The major voting event for selecting the next department heads.',
            'start_date' => now()->subDays(1),
            'end_date' => now()->addDays(5),
            'status' => 'active',
            'created_by' => $admin->id,
        ]);

        // 3. Create Nominees for this session
        $nominees = [];
        $candidates = [
            ['name' => 'Dr. Arslan Ahmed', 'designation' => 'Senior Researcher'],
            ['name' => 'Engr. Sarah Khan', 'designation' => 'Chief Architect'],
            ['name' => 'Prof. Michael Brown', 'designation' => 'Department Head'],
        ];

        foreach ($candidates as $candidate) {
            $nominees[] = Nominee::create(array_merge($candidate, [
                'voting_session_id' => $session->id,
                'country' => 'Pakistan',
                'city' => 'Islamabad',
            ]));
        }

        // 4. Create Voters and Invitations
        $voters = Voter::factory()->count(50)->create();

        foreach ($voters as $index => $voter) {
            $invitation = Invitation::create([
                'voting_session_id' => $session->id,
                'voter_id' => $voter->id,
                'token' => Str::random(64),
                'status' => $index < 20 ? 'voted' : 'pending',
                'voted_at' => $index < 20 ? now()->subHours(rand(1, 24)) : null,
                'expires_at' => $session->end_date,
            ]);

            // 5. Create Some Initial Votes
            if ($index < 20) {
                Vote::create([
                    'voting_session_id' => $session->id,
                    'nominee_id' => $nominees[array_rand($nominees)]->id,
                    'invitation_id' => $invitation->id,
                ]);
            }
        }
    }
}
