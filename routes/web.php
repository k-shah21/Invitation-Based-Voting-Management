<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\VotingSessionController;
use App\Http\Controllers\Admin\NomineeController;
use App\Http\Controllers\Admin\VoterController;
use App\Http\Controllers\Admin\InvitationController;
use App\Http\Controllers\PublicVotingController;

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/reports', [DashboardController::class, 'reports'])->name('reports');
    Route::get('/results/data', [DashboardController::class, 'getResults'])->name('results.data');

    Route::resource('sessions', VotingSessionController::class);
    Route::post('sessions/{session}/status', [VotingSessionController::class, 'updateStatus'])->name('sessions.status');

    Route::resource('nominees', NomineeController::class);

    Route::get('voters', [VoterController::class, 'index'])->name('voters.index');
    Route::post('voters', [VoterController::class, 'store'])->name('voters.store');
    Route::post('voters/import', [VoterController::class, 'import'])->name('voters.import');
    Route::delete('voters/{voter}', [VoterController::class, 'destroy'])->name('voters.destroy');

    Route::get('invitations', [InvitationController::class, 'index'])->name('invitations.index');
    Route::post('invitations/send', [InvitationController::class, 'send'])->name('invitations.send');
});



Route::middleware(['voting.token', 'throttle:10,1'])->group(function () {
    Route::get('/vote/{token}', [PublicVotingController::class, 'show'])->name('vote.show');
    Route::post('/vote/{token}', [PublicVotingController::class, 'submit'])->name('vote.submit');
});
