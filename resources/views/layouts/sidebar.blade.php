@php
    $websites = $websites ?? \App\Models\Website::where('user_id', Auth::id())->latest()->get();
    $testDefinitions = $testDefinitions ?? \App\Models\TestDefinition::whereHas('website', function($q) {
        $q->where('user_id', Auth::id());
    })->latest()->get();
@endphp

<aside class="w-80 bg-white border-r border-gray-100 flex flex-col flex-shrink-0 relative z-20 h-full">
    <div class="flex-1 overflow-y-auto custom-scrollbar">
        <!-- Navigation Menu -->
        <div class="p-6 border-b border-gray-50">
            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Main Menu</h3>
            <nav class="space-y-1">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:bg-gray-50 hover:text-indigo-600' }} transition-all duration-200 group">
                    <svg class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="text-sm font-bold">Dashboard</span>
                </a>

                <a href="{{ route('websites.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('websites.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:bg-gray-50 hover:text-indigo-600' }} transition-all duration-200 group">
                    <svg class="w-5 h-5 {{ request()->routeIs('websites.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                    </svg>
                    <span class="text-sm font-bold">Websites</span>
                </a>

                <a href="{{ route('test-definitions.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('test-definitions.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:bg-gray-50 hover:text-indigo-600' }} transition-all duration-200 group">
                    <svg class="w-5 h-5 {{ request()->routeIs('test-definitions.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <span class="text-sm font-bold">Test Definitions</span>
                </a>

                <a href="{{ route('reports.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('reports.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:bg-gray-50 hover:text-indigo-600' }} transition-all duration-200 group">
                    <svg class="w-5 h-5 {{ request()->routeIs('reports.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="text-sm font-bold">Reports</span>
                </a>
            </nav>
        </div>

        <!-- Quick Access: Websites -->
        <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-white/50 backdrop-blur-sm sticky top-0 z-10">
            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">My Websites</h3>
            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-50 text-green-600">
                {{ $websites->count() }}
            </span>
        </div>
        <div class="p-4 space-y-2 border-b border-gray-50">
            @forelse($websites->take(5) as $website)
                <a href="{{ route('websites.show', $website) }}" class="group flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-all border border-transparent hover:border-gray-100">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold text-xs flex-shrink-0">
                        {{ strtoupper(substr(parse_url($website->url, PHP_URL_HOST) ?? $website->url, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-[12px] font-bold text-gray-700 truncate group-hover:text-indigo-600 transition-colors">
                            {{ parse_url($website->url, PHP_URL_HOST) ?? $website->url }}
                        </div>
                        <div class="text-[10px] text-gray-400 font-medium tracking-tight">
                            {{ $website->test_definitions_count }} tests
                        </div>
                    </div>
                </a>
            @empty
                <p class="text-[10px] text-center py-4 text-gray-400 font-medium uppercase tracking-wider">No websites added</p>
            @endforelse
            @if($websites->count() > 5)
                <div class="px-3 py-2">
                    <a href="{{ route('websites.index') }}" class="text-[10px] font-bold text-indigo-600 hover:text-indigo-700">View all &rarr;</a>
                </div>
            @endif
        </div>

        <!-- Quick Access: Test Definitions -->
        <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-white/50 backdrop-blur-sm sticky top-0 z-10">
            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Recent Tests</h3>
            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-600">
                {{ $testDefinitions->count() }}
            </span>
        </div>
        
        <div class="p-4 space-y-3">
            @forelse($testDefinitions->take(5) as $td)
                <a href="{{ route('test-definitions.show', $td) }}" class="group block p-4 rounded-2xl border border-gray-50 hover:border-indigo-100 hover:bg-indigo-50/40 transition-all duration-300">
                    <div class="flex items-start justify-between mb-2">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider group-hover:text-indigo-500 transition-colors">
                            {{ parse_url($td->website->url, PHP_URL_HOST) }}
                        </span>
                        @if($td->testCases->first()?->testRuns?->first())
                            <span class="w-1.5 h-1.5 rounded-full {{ $td->testCases->first()->testRuns->first()->result == 'pass' ? 'bg-green-400' : 'bg-red-400' }}"></span>
                        @endif
                    </div>
                    <h4 class="text-[11px] font-bold text-gray-700 group-hover:text-indigo-900 transition-colors line-clamp-1 leading-snug">
                        {{ $td->description }}
                    </h4>
                </a>
            @empty
                <div class="text-center py-8 px-6">
                    <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">No tests yet</h4>
                    <a href="{{ route('test-definitions.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-bold rounded-xl transition-all shadow-lg shadow-indigo-100">
                        Create &rarr;
                    </a>
                </div>
            @endforelse
            @if($testDefinitions->count() > 5)
                <div class="px-3 py-1">
                    <a href="{{ route('test-definitions.index') }}" class="text-[10px] font-bold text-indigo-600 hover:text-indigo-700">View all &rarr;</a>
                </div>
            @endif
        </div>
    </div>
</aside>
