<x-app-layout mainClass="py-0">
    <div class="flex h-[calc(100vh-81px)] overflow-hidden">
        <!-- Sidebar: Websites & Test Definitions -->
        <aside class="w-80 bg-white border-r border-gray-100 flex flex-col flex-shrink-0 shadow-sm relative z-20">
            <div class="flex-1 overflow-y-auto custom-scrollbar">
                <!-- Analytics Section -->
                <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-white/50 backdrop-blur-sm sticky top-0 z-10">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest">Analytics</h3>
                    <div class="flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                        <span class="text-[10px] font-bold text-green-600 uppercase">Live</span>
                    </div>
                </div>
                <div class="p-4 grid grid-cols-2 gap-3 border-b border-gray-50">
                    <div class="p-3 rounded-2xl bg-indigo-50/50 border border-indigo-100/50">
                        <div class="text-[10px] font-bold text-indigo-400 uppercase tracking-tight">Avg Success</div>
                        <div class="text-lg font-black text-indigo-700">94.2%</div>
                    </div>
                    <div class="p-3 rounded-2xl bg-purple-50/50 border border-purple-100/50">
                        <div class="text-[10px] font-bold text-purple-400 uppercase tracking-tight">Active Agents</div>
                        <div class="text-lg font-black text-purple-700">12</div>
                    </div>
                    <div class="p-3 rounded-2xl bg-blue-50/50 border border-blue-100/50">
                        <div class="text-[10px] font-bold text-blue-400 uppercase tracking-tight">Total Tests</div>
                        <div class="text-lg font-black text-blue-700">{{ $stats['test_runs_count'] }}</div>
                    </div>
                    <div class="p-3 rounded-2xl bg-green-50/50 border border-green-100/50">
                        <div class="text-[10px] font-bold text-green-400 uppercase tracking-tight">Sites</div>
                        <div class="text-lg font-black text-green-700">{{ $stats['websites_count'] }}</div>
                    </div>
                </div>

                <!-- Websites Section -->
                <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-white/50 backdrop-blur-sm sticky top-0 z-10">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest">My Websites</h3>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-50 text-green-600">
                        {{ $websites->count() }}
                    </span>
                </div>
                <div class="p-4 space-y-2 border-b border-gray-50">
                    @forelse($websites as $website)
                        <a href="{{ route('websites.show', $website) }}" class="group flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-all border border-transparent hover:border-gray-100">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold text-xs flex-shrink-0">
                                {{ strtoupper(substr(parse_url($website->url, PHP_URL_HOST) ?? $website->url, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-bold text-gray-700 truncate group-hover:text-indigo-600 transition-colors">
                                    {{ parse_url($website->url, PHP_URL_HOST) ?? $website->url }}
                                </div>
                                <div class="text-[10px] text-gray-400 font-medium tracking-tight">
                                    {{ $website->test_definitions_count }} tests defined
                                </div>
                            </div>
                        </a>
                    @empty
                        <p class="text-xs text-center py-4 text-gray-400">No websites added yet.</p>
                    @endforelse
                </div>

                <!-- Test Definitions Section -->
                <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-white/50 backdrop-blur-sm sticky top-0 z-10">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest">Test Definitions</h3>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-600">
                        {{ $testDefinitions->count() }}
                    </span>
                </div>
                
                <div class="p-4 space-y-3">
                    @forelse($testDefinitions as $td)
                        <a href="{{ route('test-definitions.show', $td) }}" class="group block p-4 rounded-2xl border border-gray-100 hover:border-indigo-100 hover:bg-indigo-50/40 transition-all duration-300">
                            <div class="flex items-start justify-between mb-2">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider group-hover:text-indigo-500 transition-colors">
                                    {{ parse_url($td->website->url, PHP_URL_HOST) }}
                                </span>
                                @if($td->testCases->first()?->testRuns?->first())
                                    <span class="w-2 h-2 rounded-full {{ $td->testCases->first()->testRuns->first()->result == 'pass' ? 'bg-green-400' : 'bg-red-400' }}"></span>
                                @endif
                            </div>
                            <h4 class="text-sm font-bold text-gray-700 group-hover:text-indigo-900 transition-colors line-clamp-2 leading-snug">
                                {{ $td->description }}
                            </h4>
                            <div class="mt-4 flex items-center justify-between">
                                <div class="flex items-center gap-1.5 text-[10px] text-gray-400 font-medium">
                                    <svg class="w-3 h-3 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $td->created_at->diffForHumans() }}
                                </div>
                                <svg class="w-4 h-4 text-gray-300 group-hover:text-indigo-400 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-8 px-6">
                            <h4 class="text-xs font-bold text-gray-900 mb-2">No tests yet</h4>
                            <a href="{{ route('test-definitions.create') }}" class="inline-flex items-center justify-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-bold rounded-lg transition-all">
                                Create &rarr;
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto bg-[#fafafa] custom-scrollbar relative">
            <div class="p-8 lg:p-12 max-w-6xl mx-auto">
                <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
                    <div class="p-8 lg:p-12">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
                            <div>
                                <h1 class="text-4xl font-black text-gray-900 tracking-tight mb-3">Welcome back, {{ Auth::user()->name }}!</h1>
                                <p class="text-lg text-gray-500 font-medium">Your testing agent is ready to assist you.</p>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="px-6 py-4 rounded-2xl bg-gray-50 border border-gray-100 flex items-center gap-4">
                                    <div class="bg-green-100 p-2 rounded-lg">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <div>
                                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Agent Status</div>
                                        <div class="text-sm font-bold text-gray-900">Online & Ready</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="group p-8 rounded-3xl bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 text-white shadow-2xl shadow-indigo-200 relative overflow-hidden">
                                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white/10 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700"></div>
                                
                                <h3 class="text-2xl font-bold mb-4 relative z-10 text-white">Quick Actions</h3>
                                <div class="space-y-4 mt-6 relative z-10">
                                    <a href="{{ route('test-definitions.create') }}" class="flex items-center gap-4 p-4 bg-white/10 hover:bg-white/20 rounded-2xl transition-all group/btn">
                                        <span class="bg-white/20 p-3 rounded-xl group-hover/btn:bg-white group-hover/btn:text-indigo-600 transition-all font-bold">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        </span>
                                        <span class="text-lg font-bold">Create New Test</span>
                                    </a>
                                    <a href="{{ route('websites.index') }}" class="flex items-center gap-4 p-4 bg-white/10 hover:bg-white/20 rounded-2xl transition-all group/btn">
                                        <span class="bg-white/20 p-3 rounded-xl group-hover/btn:bg-white group-hover/btn:text-indigo-600 transition-all font-bold text-white">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                                        </span>
                                        <span class="text-lg font-bold">Manage Websites</span>
                                    </a>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-6">
                                <div class="p-6 rounded-3xl bg-white border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                                    <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center mb-4">
                                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                    </div>
                                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Runs</div>
                                    <div class="text-3xl font-black text-gray-900 mt-1">{{ $stats['test_runs_count'] }}</div>
                                </div>
                                
                                <div class="p-6 rounded-3xl bg-white border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                                    <div class="w-12 h-12 rounded-2xl bg-orange-50 flex items-center justify-center mb-4">
                                        <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                                    </div>
                                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Websites</div>
                                    <div class="text-xl font-black text-gray-900 mt-1">{{ $stats['websites_count'] }}</div>
                                </div>
                                
                                <div class="col-span-2 p-6 rounded-3xl bg-gray-900 text-white flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="bg-gray-800 p-2 rounded-xl">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <div class="text-sm font-medium">Need help? Explorer our documentation</div>
                                    </div>
                                    <a href="{{ route('docs') }}" class="text-xs font-bold uppercase tracking-widest hover:text-indigo-400 transition-colors">Read Docs</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Recent Tests Preview --}}
                <div class="mt-12">
                    <div class="flex items-center justify-between mb-8 px-2">
                        <h2 class="text-2xl font-black text-gray-900 tracking-tight">Recent Test Activity</h2>
                        <a href="{{ route('reports.dashboard') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-700">View all reports &rarr;</a>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @forelse($recentRuns as $run)
                            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $run->result == 'pass' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $run->result ?? 'Running' }}
                                    </span>
                                    <span class="text-xs text-gray-400 font-medium">{{ $run->created_at->diffForHumans() }}</span>
                                </div>
                                <h4 class="font-bold text-gray-900 line-clamp-1 mb-2">{{ $run->testCase?->testDefinition?->description ?? 'System Test' }}</h4>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-4">{{ parse_url($run->testCase?->testDefinition?->website?->url, PHP_URL_HOST) ?? 'Unknown site' }}</p>
                                <a href="{{ route('reports.show', $run->id) }}" class="text-xs font-bold text-indigo-600 hover:underline">View Results &rarr;</a>
                            </div>
                        @empty
                            <div class="col-span-3 py-12 text-center bg-white rounded-3xl border border-dashed border-gray-200">
                                <p class="text-gray-400 font-medium">No recent activity found.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </main>
    </div>

    @push('scripts')
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #d1d5db;
        }
        
        main {
            scrollbar-gutter: stable;
        }
    </style>
    @endpush
</x-app-layout>
