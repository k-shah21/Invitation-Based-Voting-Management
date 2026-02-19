<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nominee;
use App\Models\VotingSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NomineeController extends Controller
{
    public function index(Request $request)
    {
        $query = Nominee::with('session');

        if ($request->filled('session_id')) {
            $query->where('voting_session_id', $request->session_id);
        }

        $nominees = $query->latest()->paginate(10);
        $sessions = VotingSession::all();

        return view('admin.nominees.index', compact('nominees', 'sessions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'voting_session_id' => 'required|exists:voting_sessions,id',
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('nominees', 'public');
        }

        Nominee::create($validated);

        return back()->with('success', 'Nominee added successfully.');
    }

    public function destroy(Nominee $nominee)
    {
        if ($nominee->image) {
            Storage::disk('public')->delete($nominee->image);
        }
        $nominee->delete();
        return back()->with('success', 'Nominee deleted.');
    }
}
