@extends('layouts.admin')
@section('title', 'Dashboard')
@section('subtitle', 'Welcome back, System Admin')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stat Card 1 -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow group relative overflow-hidden">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-slate-500 mb-1">Total Sessions</p>
                <h3 class="text-3xl font-bold text-slate-800">{{ $stats['total_sessions'] }}</h3>
            </div>
            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl group-hover:scale-110 transition-transform">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded w-fit">
            <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
            </svg>
            <span>Active Now</span>
        </div>
        <div class="absolute -bottom-4 -right-4 h-24 w-24 bg-blue-50 rounded-full opacity-50 z-0 pointer-events-none"></div>
    </div>

    <!-- Stat Card 2 -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow group relative overflow-hidden">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-slate-500 mb-1">Total Nominees</p>
                <h3 class="text-3xl font-bold text-slate-800">{{ $stats['total_nominees'] }}</h3>
            </div>
            <div class="p-3 bg-purple-50 text-purple-600 rounded-xl group-hover:scale-110 transition-transform">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs font-medium text-slate-400">
            <span>Across all sessions</span>
        </div>
        <div class="absolute -bottom-4 -right-4 h-24 w-24 bg-purple-50 rounded-full opacity-50 z-0 pointer-events-none"></div>
    </div>

    <!-- Stat Card 3 -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow group relative overflow-hidden">
         <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-slate-500 mb-1">Registered Voters</p>
                <h3 class="text-3xl font-bold text-slate-800">{{ $stats['total_voters'] }}</h3>
            </div>
            <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl group-hover:scale-110 transition-transform">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs font-medium text-slate-400">
            <span>Total valid emails</span>
        </div>
        <div class="absolute -bottom-4 -right-4 h-24 w-24 bg-indigo-50 rounded-full opacity-50 z-0 pointer-events-none"></div>
    </div>

    <!-- Stat Card 4 -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow group relative overflow-hidden">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-slate-500 mb-1">Total Votes Cast</p>
                <h3 class="text-3xl font-bold text-slate-800">{{ $stats['total_votes'] }}</h3>
            </div>
            <div class="p-3 bg-orange-50 text-orange-600 rounded-xl group-hover:scale-110 transition-transform">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
         <div class="mt-4 flex items-center justify-between">
            <div class="flex items-center text-xs font-medium {{ $stats['participation_rate'] > 50 ? 'text-green-600' : 'text-orange-600' }}">
                <span>{{ $stats['participation_rate'] }}% Participation</span>
            </div>
            <div class="w-16 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full {{ $stats['participation_rate'] > 50 ? 'bg-green-500' : 'bg-orange-500' }}" style="width: {{ $stats['participation_rate'] }}%"></div>
            </div>
        </div>
        <div class="absolute -bottom-4 -right-4 h-24 w-24 bg-orange-50 rounded-full opacity-50 z-0 pointer-events-none"></div>
    </div>
</div>

<div class="grid grid-cols-1 gap-6">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Voting Results Analysis</h3>
                <p class="text-sm text-slate-500">Real-time vote distribution across nominees</p>
            </div>
            
            <form id="chartFilters" class="flex flex-wrap gap-3">
                <div class="relative">
                    <select name="session_id" class="appearance-none pl-4 pr-10 py-2.5 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all cursor-pointer shadow-sm hover:border-slate-300">
                        <option value="">All Sessions</option>
                        @foreach($sessions as $session)
                            <option value="{{ $session->id }}">{{ $session->title }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
                
                <div class="relative">
                    <input type="date" name="start_date" class="pl-4 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-sm hover:border-slate-300 placeholder-slate-400">
                </div>

                <div class="relative">
                    <input type="date" name="end_date" class="pl-4 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-sm hover:border-slate-300 placeholder-slate-400">
                </div>
            </form>
        </div>

        <div class="relative h-[400px] w-full">
            <canvas id="resultsChart"></canvas>
            <!-- Loading Overlay for Chart -->
            <div id="chartLoading" class="absolute inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center transition-opacity duration-300 opacity-0 pointer-events-none">
                <div class="flex flex-col items-center">
                    <svg class="animate-spin h-8 w-8 text-blue-600 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-sm font-medium text-slate-500">Updating Data...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let resultsChart;
    const ctx = document.getElementById('resultsChart');
    const filters = document.getElementById('chartFilters');
    const loadingOverlay = document.getElementById('chartLoading');

    async function updateChart() {
        // Show loading state
        loadingOverlay.classList.remove('opacity-0', 'pointer-events-none');
        
        try {
            const params = new URLSearchParams(new FormData(filters));
            const response = await fetch(`{{ route('admin.results.data') }}?${params.toString()}`);
            const data = await response.json();

            const labels = data.map(item => item.name);
            const votes = data.map(item => item.votes);

            if (resultsChart) {
                resultsChart.destroy();
            }

            // Create gradient
            let gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(59, 130, 246, 0.8)'); // Blue-500
            gradient.addColorStop(1, 'rgba(59, 130, 246, 0.2)');

            resultsChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Votes',
                        data: votes,
                        backgroundColor: gradient,
                        hoverBackgroundColor: '#2563eb',
                        borderColor: '#2563eb',
                        borderWidth: 1,
                        borderRadius: {
                            topLeft: 8,
                            topRight: 8,
                            bottomLeft: 4,
                            bottomRight: 4
                        },
                        borderSkipped: false,
                        barThickness: 50,
                        maxBarThickness: 70
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            padding: 16,
                            titleFont: { family: 'Inter', size: 13, weight: '600' },
                            bodyFont: { family: 'Inter', size: 13 },
                            cornerRadius: 12,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' Votes';
                                }
                            }
                        }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true,
                            grid: { 
                                borderDash: [4, 4], 
                                color: '#f1f5f9',
                                drawBorder: false
                            },
                            ticks: { 
                                color: '#64748b',
                                font: { family: 'Inter', size: 11 },
                                padding: 10
                            },
                            border: { display: false }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { 
                                color: '#64748b', 
                                font: { family: 'Inter', size: 12, weight: '500' },
                                padding: 10
                            },
                            border: { display: false }
                        }
                    },
                    animation: {
                        duration: 800,
                        easing: 'easeOutQuart'
                    },
                    hover: {
                        mode: 'index',
                        intersect: false
                    }
                }
            });
        } catch (error) {
            console.error("Failed to fetch chart data:", error);
        } finally {
            // Hide loading state
            setTimeout(() => {
                loadingOverlay.classList.add('opacity-0', 'pointer-events-none');
            }, 300);
        }
    }

    filters.addEventListener('change', updateChart);
    // Initial load
    updateChart();
</script>
@endsection
