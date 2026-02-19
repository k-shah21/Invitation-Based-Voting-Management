<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voter;
use App\Services\InvitationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VoterController extends Controller
{
    protected $invitationService;

    public function __construct(InvitationService $invitationService)
    {
        $this->invitationService = $invitationService;
    }

    public function index()
    {
        $voters = Voter::latest()->paginate(20);
        return view('admin.voters.index', compact('voters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:voters,email',
            'phone' => 'nullable|string|max:20',
        ]);

        Voter::create($validated);

        return back()->with('success', 'Voter added successfully.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        
        // Skip header
        fgetcsv($handle);

        $successCount = 0;
        $errorCount = 0;

        while (($data = fgetcsv($handle)) !== false) {
            if (count($data) < 3) {
                $errorCount++;
                continue;
            }

            $voterData = [
                'name' => $data[0],
                'email' => $data[1],
                'phone' => $data[2],
            ];

            $validator = Validator::make($voterData, [
                'email' => 'required|email',
            ]);

            if ($validator->fails()) {
                $errorCount++;
                continue;
            }

            $this->invitationService->processVoterImport($voterData);
            $successCount++;
        }

        fclose($handle);

        return back()->with('success', "Import completed. Success: $successCount, Errors/Duplicates: $errorCount");
    }

    public function destroy(Voter $voter)
    {
        $voter->delete();
        return back()->with('success', 'Voter removed successfully.');
    }
}
