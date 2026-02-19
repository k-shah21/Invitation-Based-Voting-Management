<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote Confirmed — VoteAdmin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="min-h-screen bg-linear-to-br from-slate-50 via-green-50/30 to-slate-100 flex items-center justify-center p-6">

    <div class="w-full max-w-md text-center">
        <!-- Animated Checkmark Card -->
        <div class="bg-white rounded-3xl shadow-2xl shadow-green-100/80 border border-green-100/60 p-10">
            <!-- Icon -->
            <div class="flex justify-center mb-6">
                <div class="relative h-24 w-24">
                    <div class="absolute inset-0 rounded-full bg-green-100 animate-ping opacity-20"></div>
                    <div class="relative h-24 w-24 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="h-12 w-12 text-green-600" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" style="stroke-dasharray: 30; stroke-dashoffset: 30; animation: drawCheck 0.5s ease forwards 0.3s; "/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Text content -->
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-2">Vote Recorded!</h1>
            <p class="text-slate-500 leading-relaxed max-w-xs mx-auto mt-2">
                Your vote has been securely submitted. This link has been deactivated and cannot be used again.
            </p>

            <!-- Divider -->
            <div class="my-8 h-px bg-linear-to-r from-transparent via-slate-200 to-transparent"></div>

            <!-- Info Box -->
            <div class="flex items-start gap-3 p-4 bg-blue-50 rounded-2xl border border-blue-100 text-left">
                <svg class="h-5 w-5 text-blue-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-sm text-blue-700 font-medium">Results will be published after the voting session ends.</p>
            </div>
        </div>

        <p class="text-xs text-slate-400 mt-6">
            Powered by <strong class="text-slate-500">VoteAdmin</strong> — Secure Voting Platform
        </p>
    </div>

    <style>
        @keyframes drawCheck {
            to { stroke-dashoffset: 0; }
        }
    </style>
</body>
</html>
