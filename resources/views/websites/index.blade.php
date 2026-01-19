<x-dashboard-layout>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
        <div>
            <h1 class="text-4xl font-black text-gray-900 tracking-tight mb-2 bg-clip-text text-transparent bg-gradient-to-r from-gray-900 via-indigo-900 to-indigo-600">My Websites</h1>
            <p class="text-gray-500 font-medium text-lg">Manage and monitor your connected digital assets.</p>
        </div>
        <a href="{{ route('websites.create') }}" class="group relative px-8 py-4 bg-gradient-to-r from-pink-500 to-purple-500 text-white font-bold rounded-2xl shadow-xl shadow-purple-200 hover:shadow-purple-300 transition-all hover:-translate-y-1 active:translate-y-0 overflow-hidden">
            <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-white/0 via-white/10 to-white/0 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
            <div class="flex items-center gap-2 relative z-10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                <span>Add New Website</span>
            </div>
        </a>
    </div>

    @if(session('success'))
        <div class="mb-10 animate-in fade-in slide-in-from-top-4 duration-500">
            <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-6 py-4 rounded-[2rem] flex items-center gap-4 shadow-sm">
                <div class="w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span class="font-bold text-lg">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Search and Filter Section -->
    <div class="mb-12 bg-white/80 backdrop-blur-xl rounded-[2.5rem] shadow-sm border border-gray-100 p-8">
        <form method="GET" action="{{ route('websites.index') }}" class="space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <div class="md:col-span-6">
                    <label for="search" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-3 ml-1">Search Website</label>
                    <div class="relative group">
                        <input 
                            type="text" 
                            name="search" 
                            id="search"
                            value="{{ request('search') }}"
                            placeholder="Search by name or URL..."
                            class="w-full pl-14 pr-6 py-4 rounded-2xl border-gray-100 bg-gray-50/50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-semibold text-gray-700 placeholder-gray-400 outline-none"
                        >
                        <div class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-3">
                    <label for="status" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-3 ml-1">Filter Status</label>
                    <div class="relative">
                        <select 
                            name="status" 
                            id="status"
                            class="w-full appearance-none bg-none pl-6 pr-12 py-4 rounded-2xl border-gray-100 bg-gray-50/50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-semibold text-gray-700 outline-none cursor-pointer"
                        >
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="error" {{ request('status') === 'error' ? 'selected' : '' }}>Error</option>
                        </select>
                        <div class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-3">
                    <label for="sort_by" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-3 ml-1">Sort Websites</label>
                    <div class="relative">
                        <select 
                            name="sort_by" 
                            id="sort_by"
                            class="w-full appearance-none bg-none pl-6 pr-12 py-4 rounded-2xl border-gray-100 bg-gray-50/50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-semibold text-gray-700 outline-none cursor-pointer"
                        >
                            <option value="created_at" {{ request('sort_by', 'created_at') === 'created_at' ? 'selected' : '' }}>Recent First</option>
                            <option value="url" {{ request('sort_by') === 'url' ? 'selected' : '' }}>URL Alphabetic</option>
                            <option value="status" {{ request('sort_by') === 'status' ? 'selected' : '' }}>Sort by Status</option>
                        </select>
                        <div class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4 pt-6 border-t border-gray-50">
                <button type="submit" class="px-10 py-4 bg-gradient-to-r from-pink-500 to-purple-500 text-white font-bold rounded-2xl transition-all hover:shadow-xl active:scale-95 shadow-lg shadow-purple-200 hover:shadow-purple-300">
                    Filter Results
                </button>
                @if(request()->hasAny(['search', 'status', 'sort_by']))
                    <a href="{{ route('websites.index') }}" class="px-8 py-4 bg-white text-gray-600 font-bold border border-gray-100 rounded-2xl hover:bg-gray-50 transition-all active:scale-95">
                        Reset Filters
                    </a>
                @endif
            </div>
        </form>
    </div>

    @if($websites->isEmpty())
         <div class="p-20 bg-white rounded-[3rem] shadow-sm border border-gray-100 text-center relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
            <div class="relative z-10">
                <div class="w-24 h-24 bg-gradient-to-tr from-indigo-500 to-indigo-600 rounded-[2.5rem] flex items-center justify-center mx-auto mb-8 transform rotate-6 group-hover:rotate-12 transition-transform duration-500 shadow-xl shadow-indigo-100">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                </div>
                <h3 class="text-3xl font-black text-gray-900 mb-4">Launch Your First Website</h3>
                <p class="text-gray-500 font-medium mb-10 max-w-md mx-auto text-xl leading-relaxed">It looks like you haven't added any websites yet. Connect your first site to start the automated testing engine.</p>
                <a href="{{ route('websites.create') }}" class="inline-flex items-center gap-3 px-10 py-5 bg-gradient-to-r from-pink-500 to-purple-500 text-white font-black rounded-2xl hover:shadow-purple-300 transition-all shadow-2xl shadow-purple-200 active:scale-95">
                    <span>Connect Website Now</span>
                    <svg class="w-6 h-6 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2 gap-10">
            @foreach($websites as $website)
                @php
                    $statusColors = [
                        'active' => ['bg' => 'bg-emerald-100/50', 'text' => 'text-emerald-700', 'dot' => 'bg-emerald-500', 'pulse' => 'shadow-[0_0_0_rgba(16,185,129,0.4)] animate-pulse-subtle'],
                        'inactive' => ['bg' => 'bg-gray-100/50', 'text' => 'text-gray-700', 'dot' => 'bg-gray-500', 'pulse' => ''],
                        'pending' => ['bg' => 'bg-amber-100/50', 'text' => 'text-amber-700', 'dot' => 'bg-amber-500', 'pulse' => ''],
                        'error' => ['bg' => 'bg-rose-100/50', 'text' => 'text-rose-700', 'dot' => 'bg-rose-500', 'pulse' => ''],
                    ];
                    $color = $statusColors[$website->status] ?? $statusColors['inactive'];
                    $domain = parse_url($website->url, PHP_URL_HOST) ?: $website->url;
                @endphp
                <div class="group bg-white rounded-[3rem] shadow-[0_10px_40px_-15px_rgba(0,0,0,0.05)] hover:shadow-[0_20px_60px_-10px_rgba(79,70,229,0.15)] hover:-translate-y-2 transition-all duration-700 border border-gray-50 flex flex-col h-full relative overflow-hidden">
                    <div class="p-10 pb-6 flex-1">
                        <div class="flex items-start justify-between gap-4 mb-8">
                            <div class="flex items-start gap-6 min-w-0">
                                <div class="relative shrink-0">
                                    <div class="w-16 h-16 rounded-[1.5rem] bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-700 overflow-hidden shadow-inner">
                                        <img 
                                            src="https://logo.clearbit.com/{{ $domain }}?size=64" 
                                            onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ $domain }}&background=6366f1&color=fff&bold=true&rounded=true'" 
                                            alt="{{ $domain }}"
                                            class="w-10 h-10 object-contain"
                                        >
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-white rounded-lg shadow-md border border-gray-50 flex items-center justify-center">
                                        <svg class="w-3 h-3 text-indigo-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
                                    </div>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-2xl font-black text-gray-900 truncate group-hover:text-indigo-600 transition-colors duration-300 mb-1" title="{{ $website->url }}">
                                        {{ $domain }}
                                    </h3>
                                    <p class="text-sm text-gray-400 font-semibold truncate flex items-center gap-1">
                                        <span class="opacity-50">URL:</span> {{ $website->url }}
                                    </p>
                                </div>
                            </div>
                            <div class="shrink-0">
                                <div class="flex items-center gap-2.5 px-4 py-1.5 rounded-full {{ $color['bg'] }} backdrop-blur-md border border-white/50 shadow-sm">
                                    <span class="relative flex h-2 w-2">
                                        @if($website->status === 'active')
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                        @endif
                                        <span class="relative inline-flex rounded-full h-2 w-2 {{ $color['dot'] }}"></span>
                                    </span>
                                    <span class="text-[11px] font-black uppercase tracking-widest {{ $color['text'] }}">{{ $website->status }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-6">
                            <div class="p-6 rounded-[2rem] bg-gray-50/50 border border-gray-50 group-hover:bg-white group-hover:shadow-md transition-all duration-500">
                                <div class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em] mb-2">Automation</div>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-2xl font-black text-gray-900">{{ $website->test_definitions_count }}</span>
                                    <span class="text-xs font-bold text-gray-500 uppercase">Tests</span>
                                </div>
                            </div>
                            <div class="p-6 rounded-[2rem] bg-gray-50/50 border border-gray-50 group-hover:bg-white group-hover:shadow-md transition-all duration-500">
                                <div class="text-[10px] font-black text-emerald-400 uppercase tracking-[0.2em] mb-2">Coverage</div>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-2xl font-black text-gray-900">{{ $website->reports_count ?? 0 }}</span>
                                    <span class="text-xs font-bold text-gray-500 uppercase tracking-tighter">Reports</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="px-10 pb-10 mt-auto">
                        <div class="pt-8 border-t border-gray-50 flex items-center justify-between">
                            <a href="{{ route('websites.show', $website) }}" class="group/btn inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-pink-500 to-purple-500 text-white font-black text-xs uppercase tracking-[0.2em] rounded-xl shadow-md shadow-purple-200 hover:shadow-lg hover:shadow-purple-300 transition-all duration-300 hover:-translate-y-0.5">
                                View Console
                                <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                            <div class="text-right">
                                <div class="text-[9px] font-black text-gray-300 uppercase tracking-[0.1em] mb-0.5">Last Sync</div>
                                <div class="text-[11px] font-bold text-gray-400">{{ $website->updated_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($websites->hasPages())
            <div class="mt-16">
                {{ $websites->links() }}
            </div>
        @endif
    @endif

    <style>
        @keyframes pulse-subtle {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .animate-pulse-subtle {
            animation: pulse-subtle 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</x-dashboard-layout>

