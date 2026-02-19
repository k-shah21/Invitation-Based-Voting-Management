<?php

namespace App\Services;

use App\Models\Invitation;
use App\Models\Voter;
use App\Models\VotingSession;
use Illuminate\Support\Str;
use Carbon\Carbon;

class InvitationService
{
    public function generateToken(int $length = 64): string
    {
        return Str::random($length);
    }

    public function createInvitation(VotingSession $session, Voter $voter): Invitation
    {
        return Invitation::updateOrCreate(
            [
                'voting_session_id' => $session->id,
                'voter_id' => $voter->id,
            ],
            [
                'token' => $this->generateToken(),
                'status' => 'pending',
                'voted_at' => null,
                'expires_at' => $session->end_date,
            ]
        );
    }

    public function validateToken(string $token): ?Invitation
    {
        $invitation = Invitation::where('token', $token)
            ->with('session')
            ->first();

        if (!$invitation) {
            return null;
        }

        if ($invitation->status !== 'pending') {
            return null;
        }

        if ($invitation->expires_at < now() || $invitation->session->status !== 'active') {
            return null;
        }

        return $invitation;
    }

    public function processVoterImport(array $data): Voter
    {
        return Voter::updateOrCreate(
            ['email' => $data['email']],
            [
                'name' => $data['name'],
                'phone' => $data['phone'] ?? null,
            ]
        );
    }
}
