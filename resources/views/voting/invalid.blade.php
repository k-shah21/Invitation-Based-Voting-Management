<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invalid Link — VoteAdmin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="min-h-screen bg-linear-to-br from-slate-50 via-red-50/20 to-slate-100 flex items-center justify-center p-6">

    <div class="w-full max-w-md text-center">
        <div class="bg-white rounded-3xl shadow-2xl shadow-red-100/50 border border-red-100/60 p-10">
            <!-- Icon -->
            <div class="flex justify-center mb-6">
                <div class="h-20 w-20 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="h-10 w-10 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>

            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight mb-2">Link Expired or Invalid</h1>
            <p class="text-slate-500 text-sm leading-relaxed max-w-xs mx-auto mt-2">
                This invitation link has expired, already been used, or is invalid. Each link can only be used once.
            </p>

            <!-- Divider -->
            <div class="my-8 h-px bg-linear-to-r from-transparent via-slate-200 to-transparent"></div>

            <!-- Possible Reasons -->
            <div class="text-left space-y-2.5">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Possible Reasons</p>
                @foreach(['This link has already been used to cast a vote.', 'The voting session has ended.', 'The link may have been corrupted or modified.', 'The invitation has been revoked by the admin.'] as $reason)
                <div class="flex items-center gap-2.5 text-sm text-slate-600">
                    <div class="h-1.5 w-1.5 rounded-full bg-red-400 shrink-0"></div>
                    {{ $reason }}
                </div>
                @endforeach
            </div>

            <div class="mt-8 p-4 bg-amber-50 rounded-2xl border border-amber-100 text-left flex items-start gap-3">
                <svg class="h-5 w-5 text-amber-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <p class="text-sm text-amber-700 font-medium">Please contact the administrator to request a new invitation link.</p>
            </div>
        </div>

        <p class="text-xs text-slate-400 mt-6">
            Powered by <strong class="text-slate-500">VoteAdmin</strong> — Secure Voting Platform
        </p>
    </div>

</body>
</html>
