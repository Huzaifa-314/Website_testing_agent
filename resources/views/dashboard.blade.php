<x-dashboard-layout>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
        <div>
            <h1 class="text-4xl font-black tracking-tight mb-2 bg-clip-text text-transparent bg-gradient-to-r from-gray-900 via-indigo-900 to-indigo-600">Welcome back, {{ Auth::user()->name }}!</h1>
            <p class="text-lg text-gray-500 font-medium">Your testing agent is ready to assist you.</p>
        </div>
        <div class="flex items-center gap-4">
            <div class="px-6 py-4 rounded-2xl bg-white border border-gray-100 flex items-center gap-4 shadow-sm">
                <div class="bg-emerald-100 p-2 rounded-lg">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>
                    <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Agent Status</div>
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
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $run->result == 'pass' ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' }}">
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
</x-dashboard-layout>
