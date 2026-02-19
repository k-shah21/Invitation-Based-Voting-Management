<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cast Your Vote — {{ $invitation->session->title }}</title>
    <meta name="description" content="Securely cast your vote for the {{ $invitation->session->title }} voting session.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .nominee-card.selected {
            border-color: #2563eb;
            background-color: #eff6ff;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
            transform: scale(1.015);
        }
        .nominee-card {
            transition: all 0.2s ease;
        }
        .nominee-card:hover:not(.selected) {
            border-color: #bfdbfe;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.07);
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="min-h-screen bg-linear-to-br from-slate-50 via-blue-50/30 to-slate-100 flex items-start justify-center py-12 px-4">

    <div class="w-full max-w-2xl">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold uppercase tracking-widest mb-4">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                Official & Secure
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $invitation->session->title }}</h1>
            <p class="text-slate-500 mt-2 text-sm max-w-md mx-auto leading-relaxed">{{ $invitation->session->description }}</p>
        </div>

        <!-- Voting Card -->
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-200/70 overflow-hidden">
            <!-- Card Header -->
            <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/60">
                <h2 class="text-base font-bold text-slate-800">Select Your Nominee</h2>
                <p class="text-sm text-slate-500 mt-0.5">Choose exactly one candidate. This action cannot be undone.</p>
            </div>

            <form action="{{ route('vote.submit', $invitation->token) }}" method="POST" id="voteForm">
                @csrf
                <!-- Nominees Grid -->
                <div class="p-8">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" id="nomineesGrid">
                        @foreach($nominees as $nominee)
                        <label for="nominee_{{ $nominee->id }}" class="nominee-card cursor-pointer block bg-white border-2 border-slate-200 rounded-2xl overflow-hidden" id="card_{{ $nominee->id }}" onclick="selectNominee({{ $nominee->id }})">
                            <input type="radio" name="nominee_id" id="nominee_{{ $nominee->id }}" value="{{ $nominee->id }}" class="sr-only" required>
                            <!-- Image area -->
                            <div class="h-36 bg-linear-to-br from-slate-100 to-blue-50 flex items-center justify-center overflow-hidden relative">
                                @if($nominee->image)
                                    <img src="{{ asset('storage/' . $nominee->image) }}" alt="{{ $nominee->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="h-16 w-16 flex items-center justify-center rounded-full bg-white shadow-md text-3xl font-extrabold text-blue-400">
                                        {{ strtoupper(substr($nominee->name, 0, 1)) }}
                                    </div>
                                @endif
                                <!-- Selected checkmark overlay -->
                                <div class="selected-badge absolute top-3 right-3 h-7 w-7 bg-blue-600 text-white rounded-full flex items-center justify-center opacity-0 transition-all scale-50 shadow-lg shadow-blue-300/50" id="badge_{{ $nominee->id }}">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            </div>
                            <!-- Nominee Info -->
                            <div class="px-4 py-4">
                                <h3 class="font-bold text-slate-900 text-sm">{{ $nominee->name }}</h3>
                                <p class="text-xs text-slate-500 font-medium mt-0.5">{{ $nominee->designation }}</p>
                                @if($nominee->city || $nominee->country)
                                <div class="flex items-center gap-1 mt-2.5 text-xs text-slate-400">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span>{{ $nominee->city }}{{ $nominee->city && $nominee->country ? ', ' : '' }}{{ $nominee->country }}</span>
                                </div>
                                @endif
                            </div>
                        </label>
                        @endforeach
                    </div>

                    @error('nominee_id')
                        <p class="mt-3 text-sm text-red-600 font-medium text-center">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Area -->
                <div class="px-8 pb-8">
                    <button type="submit" id="submitBtn" class="w-full py-4 px-6 bg-blue-600 text-white font-bold text-base rounded-2xl hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/30 transition-all shadow-lg shadow-blue-200 active:scale-[0.99] disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        <span id="submitLabel">Select a Nominee to Vote</span>
                    </button>
                    <p class="text-center text-xs text-slate-400 mt-4">
                        <svg class="h-3.5 w-3.5 inline-block mr-1 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Your vote is encrypted and anonymous. Each link can only be used once.
                    </p>
                </div>
            </form>
        </div>

        <!-- Footer Note -->
        <p class="text-center text-xs text-slate-400 mt-6">
            Powered by <strong class="text-slate-500">VoteAdmin</strong> — Secure Voting Platform
        </p>
    </div>

    <script>
        let selectedId = null;

        function selectNominee(id) {
            // Remove selection from previous
            if (selectedId) {
                document.getElementById('card_' + selectedId).classList.remove('selected');
                const prevBadge = document.getElementById('badge_' + selectedId);
                prevBadge.classList.remove('opacity-100', 'scale-100');
                prevBadge.classList.add('opacity-0', 'scale-50');
            }

            // Apply selection
            selectedId = id;
            document.getElementById('card_' + id).classList.add('selected');
            const badge = document.getElementById('badge_' + id);
            badge.classList.remove('opacity-0', 'scale-50');
            badge.classList.add('opacity-100', 'scale-100');
            badge.style.transition = 'opacity 0.2s ease, transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1)';

            // Enable submit button
            const btn = document.getElementById('submitBtn');
            btn.disabled = false;
            document.getElementById('submitLabel').textContent = 'Submit My Vote';
        }

        // Prevent double submission
        document.getElementById('voteForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            document.getElementById('submitLabel').innerHTML = `
                <svg class="animate-spin inline-block w-5 h-5 mr-2 -mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Submitting...
            `;
        });
    </script>
</body>
</html>
