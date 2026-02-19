@extends('layouts.admin')
@section('title', 'Reports & Results')
@section('subtitle', 'Live vote tallies across all sessions')

@section('content')
<div class="space-y-6">

    {{-- Header + Chart Filter --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Vote Distribution</h2>
                <p class="text-sm text-slate-500 mt-0.5">Select a session to view the live chart.</p>
            </div>
            <select id="sessionFilter" class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition min-w-[220px]">
                <option value="">— All Sessions —</option>
                @foreach($sessions as $session)
                    <option value="{{ $session->id }}">{{ $session->title }}</option>
                @endforeach
            </select>
        </div>

        <div class="relative" style="height: 300px;">
            <div id="chartLoading" class="absolute inset-0 flex items-center justify-center bg-white/80 rounded-xl z-10 hidden">
                <svg class="animate-spin h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
            <canvas id="votesChart"></canvas>
            <div id="chartEmpty" class="absolute inset-0 flex flex-col items-center justify-center text-slate-400 hidden">
                <svg class="h-12 w-12 mb-2 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <p class="text-sm font-medium">No votes recorded yet</p>
            </div>
        </div>
    </div>

    {{-- Per-Session Result Cards --}}
    @forelse($sessionResults as $result)
    @php $session = $result['session']; $nominees = $result['nominees']; $totalVotes = $nominees->sum('votes_count'); @endphp
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        {{-- Card Header --}}
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div>
                <h3 class="font-bold text-slate-800">{{ $session->title }}</h3>
                <div class="flex items-center gap-3 mt-1">
                    @if($session->status === 'active')
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Active
                        </span>
                    @elseif($session->status === 'closed')
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-red-700 bg-red-50 px-2 py-0.5 rounded-full border border-red-200">
                            <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span> Closed
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-full border border-slate-200">
                            Draft
                        </span>
                    @endif
                    <span class="text-xs text-slate-400">{{ $totalVotes }} total vote{{ $totalVotes !== 1 ? 's' : '' }}</span>
                </div>
            </div>
            <div class="text-right">
                <p class="text-xs text-slate-500">{{ $session->start_date->format('M d') }} – {{ $session->end_date->format('M d, Y') }}</p>
            </div>
        </div>

        {{-- Nominees List --}}
        <div class="p-6">
            @if($nominees->isEmpty())
                <p class="text-sm text-slate-400 text-center py-4">No nominees added to this session yet.</p>
            @else
                <div class="space-y-4">
                    @foreach($nominees as $index => $nominee)
                    @php $pct = $totalVotes > 0 ? round(($nominee->votes_count / $totalVotes) * 100) : 0; @endphp
                    <div class="flex items-center gap-4">
                        {{-- Rank --}}
                        <div class="w-7 text-center">
                            @if($index === 0 && $nominee->votes_count > 0)
                                <span class="text-yellow-500 text-lg">🥇</span>
                            @elseif($index === 1 && $nominee->votes_count > 0)
                                <span class="text-slate-400 text-lg">🥈</span>
                            @elseif($index === 2 && $nominee->votes_count > 0)
                                <span class="text-amber-600 text-lg">🥉</span>
                            @else
                                <span class="text-xs font-bold text-slate-400">#{{ $index + 1 }}</span>
                            @endif
                        </div>

                        {{-- Avatar --}}
                        <div class="h-9 w-9 rounded-full bg-blue-50 flex items-center justify-center text-sm font-bold text-blue-600 shrink-0">
                            @if($nominee->image)
                                <img src="{{ asset('storage/' . $nominee->image) }}" class="h-9 w-9 rounded-full object-cover">
                            @else
                                {{ strtoupper(substr($nominee->name, 0, 1)) }}
                            @endif
                        </div>

                        {{-- Name + Bar --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-sm font-semibold text-slate-800 truncate">{{ $nominee->name }}</span>
                                <span class="text-xs font-bold text-slate-600 ml-2 shrink-0">{{ $nominee->votes_count }} vote{{ $nominee->votes_count !== 1 ? 's' : '' }} <span class="font-normal text-slate-400">({{ $pct }}%)</span></span>
                            </div>
                            <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-700 {{ $index === 0 ? 'bg-blue-500' : 'bg-slate-300' }}" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    @empty
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-12 text-center">
            <svg class="h-12 w-12 text-slate-200 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <p class="text-slate-500 font-medium">No sessions found.</p>
            <a href="{{ route('admin.sessions.index') }}" class="mt-3 inline-block text-sm text-blue-600 font-semibold hover:underline">Create a session →</a>
        </div>
    @endforelse

</div>

<script>
    const dataUrl = "{{ route('admin.results.data') }}";
    let chart = null;

    const ctx = document.getElementById('votesChart').getContext('2d');

    function initChart(labels, data) {
        if (chart) chart.destroy();

        const isEmpty = data.length === 0;
        document.getElementById('chartEmpty').classList.toggle('hidden', !isEmpty);
        document.getElementById('votesChart').style.opacity = isEmpty ? '0' : '1';

        if (isEmpty) return;

        chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Votes',
                    data: data,
                    backgroundColor: 'rgba(37, 99, 235, 0.85)',
                    borderRadius: 8,
                    borderSkipped: false,
                    hoverBackgroundColor: 'rgba(37, 99, 235, 1)',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: { duration: 600, easing: 'easeOutQuart' },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: { label: ctx => ` ${ctx.parsed.y} vote${ctx.parsed.y !== 1 ? 's' : ''}` }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#64748b', font: { size: 12 } } },
                    y: { beginAtZero: true, ticks: { stepSize: 1, color: '#94a3b8', font: { size: 12 } }, grid: { color: '#f1f5f9' } }
                }
            }
        });
    }

    function fetchResults(sessionId) {
        document.getElementById('chartLoading').classList.remove('hidden');
        const url = sessionId ? `${dataUrl}?session_id=${sessionId}` : dataUrl;

        fetch(url)
            .then(r => r.json())
            .then(results => {
                document.getElementById('chartLoading').classList.add('hidden');
                initChart(results.map(r => r.name), results.map(r => r.votes));
            })
            .catch(() => document.getElementById('chartLoading').classList.add('hidden'));
    }

    document.getElementById('sessionFilter').addEventListener('change', e => fetchResults(e.target.value));
    fetchResults('');
</script>
@endsection
