<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VotingSession;
use App\Services\VotingService;
use Illuminate\Http\Request;

class VotingSessionController extends Controller
{
    protected $votingService;

    public function __construct(VotingService $votingService)
    {
        $this->votingService = $votingService;
    }

    public function index()
    {
        $sessions = VotingSession::withCount(['nominees', 'votes'])->latest()->paginate(20);
        return view('admin.sessions.index', compact('sessions'));
    }

    public function edit(VotingSession $session)
    {
        return view('admin.sessions.edit', compact('session'));
    }

    public function update(Request $request, VotingSession $session)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after:start_date',
        ]);
        $session->update($validated);
        return redirect()->route('admin.sessions.index')->with('success', 'Session updated successfully.');
    }

    public function destroy(VotingSession $session)
    {
        $session->delete();
        return redirect()->route('admin.sessions.index')->with('success', 'Session deleted.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $session = $this->votingService->createSession($validated);

        return redirect()->route('admin.nominees.index')
            ->with('success', 'Voting session created successfully.');
    }

    public function updateStatus(Request $request, VotingSession $session)
    {
        // Toggle: if active → close it; otherwise → activate it
        $newStatus = $session->status === 'active' ? 'closed' : 'active';
        $this->votingService->updateStatus($session, $newStatus);
        return back()->with('success', 'Session status changed to ' . ucfirst($newStatus) . '.');
    }
}
