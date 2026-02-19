@extends('layouts.admin')
@section('title', 'Edit Session')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
            <h2 class="text-lg font-bold text-slate-800">Edit Voting Session</h2>
            <p class="text-sm text-slate-500 mt-0.5">Update the details for this session.</p>
        </div>

        <form action="{{ route('admin.sessions.update', $session) }}" method="POST" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="block text-sm font-semibold text-slate-700 mb-1.5">Session Title</label>
                <input
                    type="text"
                    name="title"
                    id="title"
                    value="{{ old('title', $session->title) }}"
                    required
                    class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition hover:bg-white"
                    placeholder="e.g. Annual Board Election 2026"
                >
                @error('title')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-semibold text-slate-700 mb-1.5">Description</label>
                <textarea
                    name="description"
                    id="description"
                    rows="3"
                    class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition hover:bg-white"
                    placeholder="Briefly describe the purpose of this session..."
                >{{ old('description', $session->description) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label for="start_date" class="block text-sm font-semibold text-slate-700 mb-1.5">Start Date</label>
                    <input
                        type="date"
                        name="start_date"
                        id="start_date"
                        value="{{ old('start_date', $session->start_date->format('Y-m-d')) }}"
                        required
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition hover:bg-white"
                    >
                    @error('start_date')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-semibold text-slate-700 mb-1.5">End Date</label>
                    <input
                        type="date"
                        name="end_date"
                        id="end_date"
                        value="{{ old('end_date', $session->end_date->format('Y-m-d')) }}"
                        required
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition hover:bg-white"
                    >
                    @error('end_date')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                <a href="{{ route('admin.sessions.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-slate-200 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-800 transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Back to Sessions
                </a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition shadow-sm shadow-blue-200 active:scale-95">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
