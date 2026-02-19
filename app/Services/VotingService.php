<?php

namespace App\Services;

use App\Models\VotingSession;
use Illuminate\Support\Facades\Auth;

class VotingService
{
    public function createSession(array $data): VotingSession
    {
        return VotingSession::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'status' => 'draft',
            'created_by' => Auth::id() ?? \App\Models\User::first()->id ?? 1,
        ]);
    }

    public function updateStatus(VotingSession $session, string $status): bool
    {
        $validStatuses = ['draft', 'active', 'closed'];
        if (!in_array($status, $validStatuses)) {
            return false;
        }

        return $session->update(['status' => $status]);
    }

    public function isSessionActive(VotingSession $session): bool
    {
        $now = now();
        return $session->status === 'active' &&
               $session->start_date <= $now &&
               $session->end_date >= $now;
    }

    public function closeExpiredSessions(): int
    {
        return VotingSession::where('status', 'active')
            ->where('end_date', '<', now())
            ->update(['status' => 'closed']);
    }
}
