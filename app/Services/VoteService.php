<?php

namespace App\Services;

use App\Models\Vote;
use App\Models\Invitation;
use App\Models\Nominee;
use Illuminate\Support\Facades\DB;

class VoteService
{
    public function castVote(Invitation $invitation, Nominee $nominee): bool
    {
        return DB::transaction(function () use ($invitation, $nominee) {
            // Re-verify invitation status within transaction to prevent race conditions
            $invitation = Invitation::lockForUpdate()->find($invitation->id);

            if ($invitation->status !== 'pending') {
                return false;
            }

            // Create the vote
            Vote::create([
                'voting_session_id' => $invitation->voting_session_id,
                'nominee_id' => $nominee->id,
                'invitation_id' => $invitation->id,
            ]);

            // Mark invitation as voted
            $invitation->update([
                'status' => 'voted',
                'voted_at' => now(),
            ]);

            return true;
        });
    }

    public function getResults(int $votingSessionId): array
    {
        return Vote::where('voting_session_id', $votingSessionId)
            ->select('nominee_id', DB::raw('count(*) as total_votes'))
            ->groupBy('nominee_id')
            ->with('nominee')
            ->get()
            ->map(function ($vote) {
                return [
                    'name' => $vote->nominee->name,
                    'votes' => $vote->total_votes,
                ];
            })
            ->toArray();
    }
}
