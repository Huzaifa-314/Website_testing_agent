<x-dashboard-layout>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
        <div>
            <h1 class="text-4xl font-black text-gray-900 tracking-tight mb-2 bg-clip-text text-transparent bg-gradient-to-r from-gray-900 via-indigo-900 to-indigo-600">
                Test Definitions
            </h1>
            <p class="text-lg text-gray-500 font-medium">Define, automate, and monitor your quality assurance tests.</p>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('test-definitions.create') }}" class="group relative px-8 py-4 bg-indigo-600 text-white font-bold rounded-2xl shadow-xl shadow-indigo-200 hover:bg-indigo-700 transition-all hover:-translate-y-1 active:translate-y-0 overflow-hidden">
                <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-white/0 via-white/10 to-white/0 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                <div class="flex items-center gap-2 relative z-10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                    <span>Create New Test</span>
                </div>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-10 bg-emerald-50 border border-emerald-100 text-emerald-800 px-6 py-4 rounded-[2rem] flex items-center gap-4 animate-in fade-in slide-in-from-top-4 duration-500">
            <div class="bg-emerald-500 p-2 rounded-xl">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Search and Filter Section - Glassmorphism -->
    <div class="mb-12 relative">
        <div class="absolute inset-0 bg-indigo-500/5 blur-3xl rounded-[3rem] -z-10"></div>
        <div class="bg-white/80 backdrop-blur-xl rounded-[2.5rem] shadow-sm border border-white p-8 lg:p-10">
            <form method="GET" action="{{ route('test-definitions.index') }}" class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="space-y-3">
                        <label for="search" class="inline-block px-3 py-1 bg-gray-50 rounded-full text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Search Description</label>
                        <div class="relative group">
                            <input 
                                type="text" 
                                name="search" 
                                id="search"
                                value="{{ request('search') }}"
                                placeholder="What are you looking for?"
                                class="w-full pl-14 pr-6 py-4 rounded-2xl border-gray-100 bg-gray-50/30 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 focus:bg-white transition-all font-semibold text-gray-700 placeholder-gray-400 shadow-inner group-hover:bg-gray-50/50"
                            >
                            <svg class="w-6 h-6 text-gray-300 group-focus-within:text-indigo-500 absolute left-6 top-1/2 -translate-y-1/2 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label for="website_id" class="inline-block px-3 py-1 bg-gray-50 rounded-full text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Filter Website</label>
                        <div class="relative group">
                            <select 
                                name="website_id" 
                                id="website_id"
                                class="w-full h-[60px] pl-6 pr-12 rounded-2xl border-gray-100 bg-gray-50/30 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 focus:bg-white transition-all font-semibold text-gray-700 appearance-none shadow-inner group-hover:bg-gray-50/50"
                            >
                                <option value="">All Websites</option>
                                @foreach($websites as $website)
                                    <option value="{{ $website->id }}" {{ request('website_id') == $website->id ? 'selected' : '' }}>
                                        {{ parse_url($website->url, PHP_URL_HOST) ?: $website->url }}
                                    </option>
                                @endforeach
                            </select>
                            <svg class="w-5 h-5 text-gray-400 absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label for="last_result" class="inline-block px-3 py-1 bg-gray-50 rounded-full text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Last Status</label>
                        <div class="relative group">
                            <select 
                                name="last_result" 
                                id="last_result"
                                class="w-full h-[60px] pl-6 pr-12 rounded-2xl border-gray-100 bg-gray-50/30 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 focus:bg-white transition-all font-semibold text-gray-700 appearance-none shadow-inner group-hover:bg-gray-50/50"
                            >
                                <option value="">All Statuses</option>
                                <option value="pass" {{ request('last_result') === 'pass' ? 'selected' : '' }}>Pass</option>
                                <option value="fail" {{ request('last_result') === 'fail' ? 'selected' : '' }}>Fail</option>
                            </select>
                            <svg class="w-5 h-5 text-gray-400 absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-50">
                    @if(request()->hasAny(['search', 'website_id', 'last_result']))
                        <a href="{{ route('test-definitions.index') }}" class="px-6 py-3 text-gray-400 hover:text-gray-900 font-bold text-sm transition-colors uppercase tracking-widest">
                            Clear All
                        </a>
                    @endif
                    <button type="submit" class="px-10 py-4 bg-gray-900 text-white font-bold rounded-2xl hover:bg-black transition-all shadow-xl shadow-gray-200">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($testDefinitions->isEmpty())
        <div class="p-20 bg-white rounded-[3rem] shadow-sm border border-gray-100 text-center relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 to-purple-500"></div>
            <div class="w-24 h-24 bg-indigo-50 rounded-[2.5rem] flex items-center justify-center mx-auto mb-8 transform -rotate-12 hover:rotate-0 transition-transform duration-500">
                <svg class="w-12 h-12 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <h3 class="text-3xl font-black text-gray-900 mb-4">No Tests Found</h3>
            <p class="text-gray-500 font-medium mb-10 max-w-sm mx-auto text-lg">Start your automation journey by creating your first test definition.</p>
            <a href="{{ route('websites.index') }}" class="inline-flex items-center gap-3 px-10 py-5 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 transition-all shadow-2xl shadow-indigo-200 hover:-translate-y-1">
                <span>Configure Websites</span>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 gap-8">
            @foreach($testDefinitions as $testDefinition)
                @php
                    $lastRun = $testDefinition->testCases->first()?->testRuns?->sortByDesc('created_at')->first();
                    $domain = parse_url($testDefinition->website->url, PHP_URL_HOST);
                @endphp
                <div class="group bg-white/70 backdrop-blur-sm rounded-[2.5rem] border border-white/50 shadow-sm hover:shadow-2xl hover:shadow-indigo-100/50 transition-all duration-500 overflow-hidden">
                    <div class="flex flex-col lg:flex-row p-4 lg:p-0">
                        <div class="lg:w-2 bg-gradient-to-b from-indigo-500/50 to-indigo-700/50"></div>
                        <div class="flex-grow p-8 lg:p-10">
                            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                                <div class="flex-grow min-w-0">
                                    <div class="flex flex-wrap items-center gap-3 mb-6">
                                        <div class="flex items-center gap-2 px-3 py-1 bg-white/80 rounded-full border border-gray-100 shadow-sm">
                                            <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></div>
                                            <span class="text-[9px] font-black uppercase tracking-widest text-indigo-600">{{ $domain }}</span>
                                        </div>
                                        @if($lastRun)
                                            <div class="flex items-center gap-2 px-3 py-1 {{ $lastRun->result == 'pass' ? 'bg-emerald-50/80 text-emerald-600 border-emerald-100/50' : 'bg-red-50/80 text-red-600 border-red-100/50' }} rounded-full border shadow-sm">
                                                <div class="w-1.5 h-1.5 rounded-full {{ $lastRun->result == 'pass' ? 'bg-emerald-500' : 'bg-red-500' }}"></div>
                                                <span class="text-[9px] font-black uppercase tracking-widest">{{ $lastRun->result }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <h3 class="text-xl font-black text-gray-900 mb-4 group-hover:text-indigo-600 transition-colors leading-tight">
                                        {{ $testDefinition->description }}
                                    </h3>
                                    <div class="flex items-center gap-4 text-gray-400">
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                            <span class="text-[10px] font-black uppercase tracking-widest">{{ $testDefinition->testCases->count() }} Steps</span>
                                        </div>
                                        <div class="w-1 h-1 rounded-full bg-gray-200"></div>
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <span class="text-[10px] font-black uppercase tracking-widest">{{ $testDefinition->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Compact Action Line -->
                                <div class="flex items-center gap-2 p-1 bg-gray-50/50 rounded-2xl border border-gray-100/50">
                                    <a href="{{ route('test-definitions.show', $testDefinition) }}" class="px-4 py-2 hover:bg-white hover:shadow-sm rounded-xl text-gray-500 hover:text-indigo-600 font-bold text-[9px] uppercase tracking-widest transition-all">
                                        Details
                                    </a>
                                    <a href="{{ route('test-definitions.edit', $testDefinition) }}" class="px-4 py-2 hover:bg-white hover:shadow-sm rounded-xl text-gray-500 hover:text-indigo-600 font-bold text-[9px] uppercase tracking-widest transition-all">
                                        Edit
                                    </a>
                                    <div class="w-px h-4 bg-gray-200 mx-1"></div>
                                    <form method="POST" action="{{ route('test-definitions.run', $testDefinition) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="px-5 py-2 bg-emerald-600 text-white font-black text-[9px] uppercase tracking-widest rounded-xl hover:bg-emerald-700 hover:shadow-emerald-100 transition-all shadow-lg shadow-emerald-50 active:scale-95 flex items-center gap-2">
                                            Run
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('test-definitions.destroy', $testDefinition) }}" class="inline" onsubmit="return confirm('Delete this test?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($testDefinitions->hasPages())
            <div class="mt-16">
                {{ $testDefinitions->links() }}
            </div>
        @endif
    @endif
</x-dashboard-layout>
