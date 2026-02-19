@extends('layouts.admin')
@section('title', 'Voting Sessions')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden" x-data="{ showCreateModal: false }">
    <!-- Header -->
    <div class="px-6 py-5 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-slate-50/50">
        <div>
            <h2 class="text-lg font-bold text-slate-800">All Sessions</h2>
            <p class="text-sm text-slate-500">Manage your voting events and timelines</p>
        </div>
        <button @click="showCreateModal = true" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-all shadow-sm hover:shadow-blue-200 focus:ring-4 focus:ring-blue-100">
            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Create Session
        </button>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    <th class="px-6 py-4">Title & Description</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Duration</th>
                    <th class="px-6 py-4 text-center">Nominees</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($sessions as $session)
                <tr class="hover:bg-slate-50/80 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="font-semibold text-slate-800 text-sm group-hover:text-blue-600 transition-colors">{{ $session->title }}</span>
                            <span class="text-xs text-slate-500 truncate max-w-xs">{{ Str::limit($session->description, 50) }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($session->status === 'draft')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                Draft
                            </span>
                        @elseif($session->status === 'active')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5 animate-pulse"></span>
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                Closed
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                         <div class="flex flex-col text-xs text-slate-500">
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                {{ $session->start_date->format('M d, Y') }}
                            </span>
                            <span class="flex items-center gap-1 mt-0.5">
                                <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $session->end_date->format('M d, Y') }}
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center justify-center h-6 px-2 text-xs font-bold text-slate-600 bg-slate-100 rounded-lg">
                            {{ $session->nominees->count() }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <form action="{{ route('admin.sessions.status', $session) }}" method="POST">
                                @csrf
                                <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-{{ $session->status === 'active' ? 'red' : 'green' }}-600 hover:bg-slate-100 transition" 
                                    title="{{ $session->status === 'active' ? 'Close Session' : 'Activate Session' }}">
                                    @if($session->status === 'active')
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @else
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @endif
                                </button>
                            </form>
                            
                            <a href="{{ route('admin.sessions.edit', $session) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition" title="Edit">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </a>
                            
                            <form action="{{ route('admin.sessions.destroy', $session) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition" title="Delete">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="h-12 w-12 bg-slate-100 rounded-full flex items-center justify-center mb-3">
                                <svg class="h-6 w-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <h3 class="text-sm font-medium text-slate-900">No voting sessions</h3>
                            <p class="text-sm text-slate-500 mt-1">Get started by creating a new session.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Create Modal -->
    <div x-show="showCreateModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showCreateModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" aria-hidden="true" @click="showCreateModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showCreateModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-slate-100">
                
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-lg font-bold text-slate-800" id="modal-title">New Voting Session</h3>
                    <p class="text-sm text-slate-500 mt-0.5">Define the details for the updated voting event.</p>
                </div>

                <form action="{{ route('admin.sessions.store') }}" method="POST" class="p-6 space-y-5">
                    @csrf
                    <div>
                        <label for="title" class="block text-sm font-semibold text-slate-700">Session Title</label>
                        <input type="text" name="title" id="title" required class="mt-1.5 block w-full rounded-xl border-slate-200 bg-slate-50 focus:border-blue-500 focus:ring-blue-500/20 text-slate-800 sm:text-sm py-2.5 px-3 shadow-sm transition-all hover:bg-white" placeholder="e.g. Annual Board Election 2026">
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-semibold text-slate-700">Description</label>
                        <textarea name="description" id="description" rows="3" class="mt-1.5 block w-full rounded-xl border-slate-200 bg-slate-50 focus:border-blue-500 focus:ring-blue-500/20 text-slate-800 sm:text-sm py-2.5 px-3 shadow-sm transition-all hover:bg-white" placeholder="Briefly describe the purpose of this session..."></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label for="start_date" class="block text-sm font-semibold text-slate-700">Start Date</label>
                            <input type="date" name="start_date" id="start_date" required class="mt-1.5 block w-full rounded-xl border-slate-200 bg-slate-50 focus:border-blue-500 focus:ring-blue-500/20 text-slate-800 sm:text-sm py-2.5 px-3 shadow-sm transition-all hover:bg-white">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-semibold text-slate-700">End Date</label>
                            <input type="date" name="end_date" id="end_date" required class="mt-1.5 block w-full rounded-xl border-slate-200 bg-slate-50 focus:border-blue-500 focus:ring-blue-500/20 text-slate-800 sm:text-sm py-2.5 px-3 shadow-sm transition-all hover:bg-white">
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3 pt-2">
                        <button type="button" @click="showCreateModal = false" class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-slate-800 transition-colors focus:ring-2 focus:ring-slate-100">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 border border-transparent rounded-xl text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm shadow-blue-200 transition-all">
                            Create Session
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
