<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nominee;
use App\Models\Voter;
use App\Models\VotingSession;
use App\Models\Vote;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_sessions' => VotingSession::count(),
            'total_nominees' => Nominee::count(),
            'total_voters' => Voter::count(),
            'total_votes' => Vote::count(),
            'participation_rate' => $this->calculateParticipationRate(),
        ];

        $sessions = VotingSession::all();

        return view('admin.dashboard', compact('stats', 'sessions'));
    }

    public function reports()
    {
        $sessions = VotingSession::withCount('votes')->latest()->get();

        // Pre-compute vote tallies per session for server-side display
        $sessionResults = $sessions->map(function ($session) {
            $nominees = \App\Models\Nominee::where('voting_session_id', $session->id)
                ->withCount('votes')
                ->orderByDesc('votes_count')
                ->get();

            return [
                'session'  => $session,
                'nominees' => $nominees,
            ];
        });

        return view('admin.reports', compact('sessions', 'sessionResults'));
    }

    public function getResults(Request $request)
    {
        $query = Vote::query();

        if ($request->has('session_id') && $request->session_id) {
            $query->where('voting_session_id', $request->session_id);
        }

        if ($request->has('start_date') && $request->start_date) {
            $query->where('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->where('created_at', '<=', $request->end_date);
        }

        $results = $query->select('nominee_id', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->groupBy('nominee_id')
            ->with('nominee')
            ->get()
            ->map(function ($vote) {
                return [
                    'name' => $vote->nominee->name,
                    'votes' => $vote->count
                ];
            });

        return response()->json($results);
    }

    private function calculateParticipationRate()
    {
        $totalVoters = Voter::count();
        if ($totalVoters === 0) return 0;
        
        $totalVotes = Vote::count();
        return round(($totalVotes / $totalVoters) * 100, 2);
    }
}
