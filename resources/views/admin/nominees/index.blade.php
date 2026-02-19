@extends('layouts.admin')
@section('title', 'Nominees')

@section('content')
<div x-data="{ showAddModal: false }">

    <!-- Filters + Action Bar -->
    <div class="flex flex-col sm:flex-row gap-3 justify-between items-start sm:items-center mb-6">
        <form method="GET" action="{{ route('admin.nominees.index') }}" class="flex flex-wrap gap-3">
            <div class="relative">
                <select name="session_id" onchange="this.form.submit()" class="appearance-none pl-4 pr-10 py-2.5 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all cursor-pointer shadow-sm hover:border-slate-300">
                    <option value="">All Sessions</option>
                    @foreach(\App\Models\VotingSession::all() as $session)
                        <option value="{{ $session->id }}" {{ request('session_id') == $session->id ? 'selected' : '' }}>{{ $session->title }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>
        </form>

        <button @click="showAddModal = true" class="inline-flex items-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-all shadow-sm hover:shadow-blue-200 active:scale-95 whitespace-nowrap">
            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Add Nominee
        </button>
    </div>

    <!-- Nominees Grid -->
    @if($nominees->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-16 text-center">
            <div class="h-14 w-14 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="h-7 w-7 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            </div>
            <h3 class="text-base font-semibold text-slate-800">No nominees yet</h3>
            <p class="text-sm text-slate-500 mt-1 mb-5">Add nominees to a session to get started.</p>
            <button @click="showAddModal = true" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Add First Nominee
            </button>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($nominees as $nominee)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group">
                <!-- Photo Area -->
                <div class="relative bg-linear-to-br from-slate-100 to-blue-50 h-44 flex items-center justify-center overflow-hidden">
                    @if($nominee->image)
                        <img src="{{ asset('storage/' . $nominee->image) }}" alt="{{ $nominee->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="h-20 w-20 rounded-full bg-white/80 flex items-center justify-center text-3xl font-bold text-slate-400 shadow-sm">
                            {{ strtoupper(substr($nominee->name, 0, 1)) }}
                        </div>
                    @endif
                    <!-- Vote Badge -->
                    <div class="absolute top-3 right-3">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-600 text-white shadow-md shadow-blue-300/40">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            {{ $nominee->votes->count() }}
                        </span>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-4">
                    <h4 class="font-bold text-slate-800 text-sm truncate">{{ $nominee->name }}</h4>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">{{ $nominee->designation }}</p>
                    <div class="flex items-center gap-1.5 mt-3">
                        <svg class="h-3.5 w-3.5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span class="text-xs text-slate-400">{{ $nominee->city }}, {{ $nominee->country }}</span>
                    </div>
                </div>

                <!-- Card Footer -->
                <div class="px-4 pb-4 flex justify-between items-center border-t border-slate-50 pt-3">
                    <span class="text-xs text-slate-400 font-medium">{{ $nominee->session->title }}</span>
                    <form action="{{ route('admin.nominees.destroy', $nominee) }}" method="POST" onsubmit="return confirm('Delete this nominee?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    @endif


    <!-- Add Nominee Modal -->
    <div x-show="showAddModal" style="display:none" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="showAddModal" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showAddModal = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div x-show="showAddModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative inline-block align-middle bg-white rounded-2xl text-left shadow-xl transform transition-all w-full max-w-lg border border-slate-100">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/60 flex justify-between items-start rounded-t-2xl">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Add New Nominee</h3>
                        <p class="text-sm text-slate-500 mt-0.5">Fill in the candidate details below.</p>
                    </div>
                    <button @click="showAddModal = false" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-200 transition">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form action="{{ route('admin.nominees.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Voting Session</label>
                        <select name="voting_session_id" required class="block w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition hover:bg-white">
                            <option value="">Select a session</option>
                            @foreach(\App\Models\VotingSession::all() as $s)
                                <option value="{{ $s->id }}" {{ request('session_id') == $s->id ? 'selected' : '' }}>{{ $s->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Full Name</label>
                            <input type="text" name="name" required class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition hover:bg-white" placeholder="e.g. Dr. Ali Hassan">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Designation</label>
                            <input type="text" name="designation" required class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition hover:bg-white" placeholder="e.g. Senior Director">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Country</label>
                            <input type="text" name="country" class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition hover:bg-white" placeholder="Pakistan">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">City</label>
                            <input type="text" name="city" class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition hover:bg-white" placeholder="Islamabad">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Profile Photo</label>
                        <label for="image_upload" id="image_upload_label" class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-slate-200 rounded-xl cursor-pointer bg-slate-50 hover:bg-slate-100 hover:border-blue-300 transition-all group overflow-hidden">
                            <div id="image_upload_placeholder" class="flex flex-col items-center justify-center text-slate-400 group-hover:text-blue-500 transition-colors">
                                <svg class="h-7 w-7 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <p class="text-xs font-medium">Click to upload image</p>
                            </div>
                            <img id="image_preview" src="#" alt="Preview" class="hidden w-full h-full object-cover">
                            <input id="image_upload" type="file" name="image" class="hidden" accept="image/*" onchange="previewNomineeImage(event)">
                        </label>
                        <p id="image_filename" class="text-xs text-slate-400 mt-1 truncate hidden"></p>
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="showAddModal = false" class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-700 hover:bg-slate-50 transition">Cancel</button>
                        <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition shadow-sm shadow-blue-200 active:scale-95">Add Nominee</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function previewNomineeImage(event) {
        const input = event.target;
        const reader = new FileReader();
        const placeholder = document.getElementById('image_upload_placeholder');
        const preview = document.getElementById('image_preview');
        const filename = document.getElementById('image_filename');
        const label = document.getElementById('image_upload_label');

        reader.onload = function(){
            preview.src = reader.result;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
            label.classList.remove('border-dashed', 'border-2');
            label.classList.add('border', 'border-solid');
        };

        if(input.files && input.files[0]) {
            reader.readAsDataURL(input.files[0]);
            filename.textContent = input.files[0].name;
            filename.classList.remove('hidden');
        }
    }
</script>
@endsection
