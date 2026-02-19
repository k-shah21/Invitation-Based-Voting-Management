@extends('layouts.admin')
@section('title', 'Invitations')

@section('content')
    <div x-data="{
                                                                                showSendModal: false,
                                                                                step: 1,
                                                                                selectedSession: '',
                                                                                selectedVoters: [],
                                                                                loading: false,
                                                                                voters: {{ $voters->toJson() }},
                                                                                get selectedCount() { return this.selectedVoters.length },
                                                                                toggleAll(e) {
                                                                                    if (e.target.checked) {
                                                                                        this.selectedVoters = this.voters.map(v => v.id.toString());
                                                                                    } else {
                                                                                        this.selectedVoters = [];
                                                                                    }
                                                                                }
                                                                            }">

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Invitation Management</h2>
                <p class="text-sm text-slate-500 mt-0.5">Send secure voting links to registered voters.</p>
            </div>
            <button @click="showSendModal = true; step = 1; selectedVoters = []; selectedSession = ''"
                class="inline-flex items-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-all shadow-sm hover:shadow-blue-200 active:scale-95">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Send Invitations
            </button>
            </div>

        <!-- Invitations Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div>
                    <h3 class="text-base font-bold text-slate-800">Sent Invitations</h3>
                    <p class="text-xs text-slate-500">Track delivery and voting status per invitation</p>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">
                    {{ $invitations->total() }} total
                </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="px-6 py-4">Voter</th>
                            <th class="px-6 py-4">Session</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Voted At</th>
                            <th class="px-6 py-4">Expires</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($invitations as $invitation)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="h-8 w-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-bold shrink-0">
                                                {{ strtoupper(substr($invitation->voter->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-semibold text-sm text-slate-800">{{ $invitation->voter->name }}</p>
                                                <p class="text-xs text-slate-400">{{ $invitation->voter->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-slate-600 font-medium">{{ Str::limit($invitation->session->title, 30) }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($invitation->status === 'voted')
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3" />
                                                </svg>
                                                Voted
                                            </span>
                                        @elseif($invitation->status === 'pending')
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3" />
                                                </svg>
                                                Pending
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3" />
                                                </svg>
                                                Expired
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="text-sm text-slate-500">{{ $invitation->voted_at ? $invitation->voted_at->format('M d, Y H:i') : '—' }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-slate-400">{{ $invitation->expires_at->format('M d, Y') }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-14 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="h-12 w-12 bg-slate-100 rounded-full flex items-center justify-center mb-3">
                                                <svg class="h-6 w-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <h3 class="text-sm font-medium text-slate-800">No invitations sent yet</h3>
                                            <p class="text-sm text-slate-400 mt-1">Click "Send Invitations" to get started.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        </table>
                        </div>
                        @if($invitations->hasPages())
                            <div class="px-6 py-4 border-t border-slate-100">{{ $invitations->links() }}</div>
                        @endif
                        </div>

        <!-- Send Invitations Modal (Step-based) -->
        <div x-show="showSendModal" style="display:none" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end sm:items-center justify-center min-h-screen px-4 pt-4 pb-0 sm:p-0 text-center">
                <div x-show="showSendModal" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showSendModal = false"></div>

                <div x-show="showSendModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95"
                    class="relative inline-block bg-white w-full max-w-lg rounded-t-2xl sm:rounded-2xl text-left shadow-2xl z-10 border border-slate-100 overflow-hidden">
                    <!-- Modal Header -->
                    <div class="px-6 pt-6 pb-5 border-b border-slate-100 bg-slate-50/60">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-xs font-semibold text-blue-600 uppercase tracking-widest mb-1">Step <span x-text="step"></span> of 2</p>
                                <h3 class="text-lg font-bold text-slate-800" x-text="step === 1 ? 'Select Session' : 'Select Voters'">
                                </h3>
                                </div>
                            <button @click="showSendModal = false"
                                class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-200 transition">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                            </div>
                            <!-- Progress Bar -->
                            <div class="mt-4 h-1 bg-slate-200 rounded-full">
                            <div class="h-1 bg-blue-500 rounded-full transition-all duration-300" :style="'width: ' + (step / 2 * 100) + '%'">
                            </div>
                            </div>
                            </div>

                    <form action="{{ route('admin.invitations.send') }}" method="POST">
                        @csrf
                        <!-- Step 1: Select Session -->
                        <div x-show="step === 1" class="p-6 space-y-4">
                            <p class="text-sm text-slate-500">Choose the voting session for which you want to send
                                invitations.</p>
                            <div class="relative">
                                <select name="session_id" x-model="selectedSession" required
                                    class="block w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 pr-10 text-sm font-medium text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition hover:bg-white">
                                    <option value="">-- Choose a session --</option>
                                    @foreach($sessions as $session)
                                        <option value="{{ $session->id }}">{{ $session->title }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                                </div>
                                <div class="flex justify-end pt-2">
                                <button type="button" @click="step = 2" :disabled="!selectedSession"
                                    class="px-5 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 disabled:opacity-40 disabled:cursor-not-allowed transition active:scale-95">
                                    Next: Choose Voters →
                                </button>
                                </div>
                                </div>

                        <!-- Step 2: Select Voters -->
                        <div x-show="step === 2" class="flex flex-col" style="max-height: 60vh; display: none;">
                            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between sticky top-0 bg-white z-10">
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                    <input type="checkbox" @change="toggleAll($event)"
                                        class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                    <span class="text-sm font-semibold text-slate-700 group-hover:text-slate-900 transition">Select
                                        All</span>
                                    </label>
                                <span class="text-sm font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full"
                                    x-text="selectedCount + ' selected'"></span>
                                </div>
                                <div class="overflow-y-auto flex-1" style="max-height: 40vh;">
                                    <ul class="divide-y divide-slate-50">
                                        @foreach($voters as $voter)
                                        <li class="px-6 py-3 hover:bg-slate-50 transition cursor-pointer" @click="$el.querySelector('input').click()">
                                            <label class="flex items-center gap-3 cursor-pointer">
                                                <input type="checkbox" name="voter_ids[]" value="{{ $voter->id }}" x-model="selectedVoters"
                                                    class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                                <div
                                                    class="h-8 w-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-bold shrink-0">
                                                    {{ strtoupper(substr($voter->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-slate-800">{{ $voter->name }}</p>
                                                    <p class="text-xs text-slate-400">{{ $voter->email }}</p>
                                                </div>
                                                </label>
                                                </li>
                                    @endforeach
                                                </ul>
                                                </div>
                            <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between bg-slate-50/60">
                                <button type="button" @click="step = 1" class="text-sm font-medium text-slate-600 hover:text-slate-800 transition">
                                    ← Back
                                </button>

                                <form method="POST" action="{{ route('admin.invitations.send') }}">
                                    @csrf

                                    <input type="hidden" name="voting_session_id" :value="selectedSession">

                                    <template x-for="voter in selectedVoters">
                                        <input type="hidden" name="voters[]" :value="voter">
                                    </template>
                                    <button type="submit" :disabled="selectedCount === 0 || loading"
                                        class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 disabled:opacity-40 disabled:cursor-not-allowed transition shadow-sm shadow-blue-200 active:scale-95">
                                        <svg x-show="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        <span
                                            x-text="loading ? 'Sending...' : 'Send ' + selectedCount + ' Invitation' + (selectedCount !== 1 ? 's' : '')"></span>
                                    </button>
                                </form>

                            </div>
                            </div>
                            </form>
                            </div>
                            </div>
                            </div>
                            </div>
@endsection