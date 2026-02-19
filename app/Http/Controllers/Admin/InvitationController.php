<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\InvitationEmail;
use App\Models\Voter;
use App\Models\VotingSession;
use App\Models\Invitation;
use App\Services\InvitationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class InvitationController extends Controller
{
    protected $invitationService;

    public function __construct(InvitationService $invitationService)
    {
        $this->invitationService = $invitationService;
    }

    public function index(Request $request)
    {
        $sessions    = VotingSession::whereIn('status', ['draft', 'active'])->latest()->get();
        $voters      = Voter::all();
        $invitations = Invitation::with(['session', 'voter'])->latest()->paginate(20);

        Mail::to('kshah@fossphorus.com');
        return view('admin.invitations.index', compact('sessions', 'voters', 'invitations'));
    }

    public function send(Request $request)
    {
       
        $request->validate([
            'voting_session_id' => 'required|exists:voting_sessions,id',
            'voter_ids'         => 'required|array',
            'voter_ids.*'       => 'exists:voters,id',
        ]);

        $exists = Invitation::where('voting_session_id', $request->voting_session_id)
            ->where('voter_id', $request->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Invitation already sent to this voter');
        }
        $session  = VotingSession::findOrFail($request->voting_session_id);
        $voterIds = $request->voter_ids;
        $sent     = 0;
        $skipped  = 0;

        foreach ($voterIds as $voterId) {
            $voter      = Voter::findOrFail($voterId);
            $invitation = $this->invitationService->createInvitation($session, $voter);

            try {
                Mail::to($voter->email)->send(new InvitationEmail($invitation));
                $sent++;
            } catch (\Exception $e) {
                // Log but don't block – invitation record is already saved
                \Log::error("Failed to send invitation email to {$voter->email}: " . $e->getMessage());
                $skipped++;
            }
        }

        $message = "Invitations sent: {$sent}.";
        if ($skipped > 0) {
            $message .= " {$skipped} email(s) could not be delivered (check your mail config).";
        }

        return back()->with('success', $message);
    }
}
