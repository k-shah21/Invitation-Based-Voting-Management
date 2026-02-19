<?php

namespace App\Http\Controllers;

use App\Models\Nominee;
use App\Services\VoteService;
use Illuminate\Http\Request;

class PublicVotingController extends Controller
{
    protected $voteService;

    public function __construct(VoteService $voteService)
    {
        $this->voteService = $voteService;
    }

    public function show(Request $request, $token)
    {
        $invitation = $request->get('invitation'); // From middleware
        $session = $invitation->session;
        $nominees = $session->nominees;

        return view('voting.show', compact('invitation', 'session', 'nominees', 'token'));
    }

    public function submit(Request $request, $token)
    {
        $request->validate([
            'nominee_id' => 'required|exists:nominees,id',
        ]);

        $invitation = $request->get('invitation'); // From middleware
        $nominee = Nominee::findOrFail($request->nominee_id);

        $success = $this->voteService->castVote($invitation, $nominee);

        if (!$success) {
            return redirect()->route('vote.show', $token)->with('error', 'Unable to cast vote. Session might be closed or link used.');
        }

        return view('voting.success');
    }
}
