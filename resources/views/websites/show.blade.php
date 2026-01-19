<x-dashboard-layout>
    <div class="mb-10">
        <nav class="flex text-sm text-gray-400 mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('websites.index') }}" class="inline-flex items-center hover:text-indigo-600 font-semibold transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Websites
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-300 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        <span class="ml-1 font-bold text-gray-500 truncate max-w-[200px]">{{ parse_url($website->url, PHP_URL_HOST) }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="flex items-center gap-6">
                <div class="w-20 h-20 rounded-[2rem] bg-indigo-600 shadow-xl shadow-indigo-200 flex items-center justify-center text-white transform rotate-3">
                     <img 
                        src="https://logo.clearbit.com/{{ parse_url($website->url, PHP_URL_HOST) }}?size=128" 
                        onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ parse_url($website->url, PHP_URL_HOST) }}&background=fff&color=4f46e5&bold=true&rounded=false'" 
                        alt="Logo"
                        class="w-12 h-12 object-contain rounded-xl"
                    >
                </div>
                <div class="min-w-0">
                    <h1 class="text-3xl font-black text-gray-900 leading-tight mb-2 truncate" title="{{ $website->url }}">{{ parse_url($website->url, PHP_URL_HOST) ?: $website->url }}</h1>
                    <div class="flex items-center gap-3 flex-wrap">
                        <span class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-black uppercase tracking-widest border border-emerald-100/50 shrink-0">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            {{ $website->status }}
                        </span>
                        <span class="text-sm font-semibold text-gray-400 truncate max-w-[200px] md:max-w-xs" title="{{ $website->url }}">{{ $website->url }}</span>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-3 w-full lg:w-auto">
                <a href="{{ route('websites.edit', $website) }}" class="flex-1 lg:flex-none text-center px-6 py-3 bg-white border border-gray-100 rounded-2xl font-bold text-sm text-gray-600 hover:bg-gray-50 hover:shadow-md transition-all active:scale-95 shadow-sm">
                    Edit Setting
                </a>
                <a href="{{ route('test-definitions.create', ['website_id' => $website->id]) }}" class="flex-1 lg:flex-none text-center px-6 py-3 bg-indigo-600 text-white font-bold rounded-2xl shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition-all hover:-translate-y-0.5 active:scale-95">
                    + Launch Test
                </a>
                <button type="button" onclick="confirmDelete()" class="p-3 bg-rose-50 text-rose-600 rounded-2xl hover:bg-rose-100 transition-colors border border-rose-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-8 animate-in fade-in slide-in-from-top-4 duration-500">
            <div class="bg-indigo-50 border border-indigo-100 text-indigo-700 px-6 py-4 rounded-3xl flex items-center gap-3 shadow-sm">
                <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
        <!-- Stats Card 1 -->
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-50 flex flex-col justify-between group hover:shadow-xl transition-all duration-500">
            <div class="flex items-center justify-between mb-6">
                <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <div class="px-3 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase tracking-widest rounded-lg">Configured</div>
            </div>
            <div>
                <div class="text-3xl font-black text-gray-900 mb-1">{{ $website->testDefinitions->count() }}</div>
                <div class="text-sm font-bold text-gray-400 uppercase tracking-widest">Active Test Scenarios</div>
            </div>
        </div>

        <!-- Stats Card 2 -->
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-50 flex flex-col justify-between group hover:shadow-xl transition-all duration-500">
            <div class="flex items-center justify-between mb-6">
                <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                 <div class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-widest rounded-lg">Total Runs</div>
            </div>
            <div>
                <div class="text-3xl font-black text-gray-900 mb-1">{{ $website->reports->count() }}</div>
                <div class="text-sm font-bold text-gray-400 uppercase tracking-widest">Automation Cycles</div>
            </div>
        </div>

        <!-- Stats Card 3 -->
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-50 flex flex-col justify-between group hover:shadow-xl transition-all duration-500">
             <div class="flex items-center justify-between mb-6">
                <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <a href="{{ route('reports.index', $website) }}" class="text-[10px] font-black text-indigo-600 uppercase tracking-widest hover:underline">Full Analytics &rarr;</a>
            </div>
            <div>
                <div class="text-3xl font-black text-gray-900 mb-1">{{ $website->reports->count() }}</div>
                <div class="text-sm font-bold text-gray-400 uppercase tracking-widest">Insight Reports</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-10">
        <!-- Main Console Section -->
        <div class="space-y-10 min-w-0">
            <!-- Test List -->
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-50 overflow-hidden">
                <div class="p-8 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
                    <div class="min-w-0">
                        <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight truncate">Automation Console</h3>
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mt-1">Manage execution flows</p>
                    </div>
                </div>

                <div class="divide-y divide-gray-50">
                    @forelse($website->testDefinitions as $test)
                        <div class="p-8 hover:bg-indigo-50/30 transition-colors group">
                            <div class="flex flex-col sm:flex-row items-start justify-between gap-6">
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-lg font-black text-gray-900 flex items-center gap-3 mb-2">
                                        <div class="w-2 h-2 rounded-full bg-indigo-400 group-hover:scale-150 transition-transform shrink-0"></div>
                                        <span class="truncate" title="{{ $test->description }}">{{ $test->description }}</span>
                                    </h4>
                                    <div class="flex flex-wrap items-center gap-4">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase bg-gray-100 px-2 py-0.5 rounded-md">ID: {{ $test->id }}</span>
                                        <span class="text-xs font-semibold text-gray-400 italic">Added {{ $test->created_at->diffForHumans() }}</span>
                                    </div>
                                    @php
                                        $steps = $test->testCases->first()->steps ?? [];
                                    @endphp
                                    <div class="mt-4 p-5 bg-gray-900 rounded-2xl border border-gray-800 font-mono text-[11px] text-gray-400 shadow-inner break-all whitespace-pre-wrap overflow-hidden ring-1 ring-white/5">
                                        <span class="text-indigo-400 font-bold">$ flow</span> = {{ json_encode($steps) }}
                                    </div>
                                </div>
                                <div class="flex flex-row sm:flex-col items-center sm:items-end gap-4 shrink-0 w-full sm:w-auto pt-4 sm:pt-0 border-t sm:border-t-0 border-gray-50">
                                    @php
                                        $lastRun = $test->testCases->first()?->testRuns?->last();
                                    @endphp
                                    @if($lastRun)
                                        <div class="text-right">
                                            <div class="text-[9px] font-black text-gray-300 uppercase tracking-widest mb-1">Status</div>
                                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase {{ $lastRun->result == 'pass' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                                {{ $lastRun->result }}
                                            </span>
                                        </div>
                                    @endif
                                    <form method="POST" action="{{ route('test-definitions.run', $test) }}">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-gray-900 text-white font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-black transition-all shadow-lg active:scale-95 group-hover:bg-indigo-600">
                                            Execute
                                            <svg class="ml-2 w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-16 text-center">
                            <div class="w-20 h-20 bg-gray-50 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-gray-300">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <h4 class="text-xl font-black text-gray-900 mb-2">No flows defined</h4>
                            <p class="text-gray-400 font-bold text-sm uppercase mb-8">Ready to start automating your workflow?</p>
                            <a href="{{ route('test-definitions.create', ['website_id' => $website->id]) }}" class="inline-flex items-center px-8 py-3 bg-indigo-50 text-indigo-600 font-black text-xs uppercase tracking-[0.2em] rounded-2xl hover:bg-indigo-600 hover:text-white transition-all">
                                Define First Test
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar Activity Logs -->
        <div class="space-y-10">
            <!-- Property Information -->
            <div class="bg-gray-900 rounded-[2.5rem] p-8 text-white shadow-2xl shadow-indigo-200 group relative overflow-hidden">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10"></div>
                <div class="relative z-10">
                    <h3 class="text-lg font-black uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                        Property Details
                    </h3>
                    <div class="space-y-6">
                        <div>
                            <div class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Hostname</div>
                            <div class="font-bold text-lg break-all text-indigo-300">{{ parse_url($website->url, PHP_URL_HOST) }}</div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 border-t border-white/5 pt-6">
                            <div>
                                <div class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Created At</div>
                                <div class="font-bold text-sm">{{ $website->created_at->format('M d, Y') }}</div>
                            </div>
                            <div>
                                <div class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Last Sync</div>
                                <div class="font-bold text-sm">{{ $website->updated_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        <div class="pt-6 border-t border-white/5">
                             <div class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Full Endpoint</div>
                             <div class="font-mono text-[11px] text-gray-400 bg-black/40 p-3 rounded-xl border border-white/5 truncate" title="{{ $website->url }}">
                                 {{ $website->url }}
                             </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Execution Logs -->
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-50 overflow-hidden">
                <div class="p-8 border-b border-gray-50 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-black text-gray-900 uppercase tracking-tight">Recent Activity</h3>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Real-time status</p>
                    </div>
                </div>
                
                <div class="p-8 space-y-6">
                    @php
                        $testRuns = $website->getTestRuns()->take(8);
                    @endphp
                    @forelse($testRuns as $run)
                         <div class="flex items-start gap-4">
                            <div class="mt-1">
                                @if($run->result === 'pass')
                                    <div class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                    </div>
                                @else
                                    <div class="w-6 h-6 rounded-full bg-rose-100 flex items-center justify-center text-rose-600">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-black text-gray-900 truncate leading-none mb-1">{{ $run->testCase->testDefinition->description ?? 'Unknown Test' }}</div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase">{{ $run->executed_at ? $run->executed_at->diffForHumans() : 'Just now' }}</span>
                                    <div class="w-1 h-1 rounded-full bg-gray-200"></div>
                                    <span class="text-[10px] font-black uppercase {{ $run->result === 'pass' ? 'text-emerald-500' : 'text-rose-500' }}">{{ $run->result }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">No recent executions</p>
                        </div>
                    @endforelse
                </div>
                 @if($testRuns->isNotEmpty())
                    <div class="px-8 pb-8">
                        <a href="{{ route('reports.index', $website) }}" class="block text-center py-4 bg-gray-50 hover:bg-gray-100 rounded-2xl text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] transition-colors">
                            View All Logs
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>


    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-5 text-center">Delete Website</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 text-center">
                        Are you sure you want to delete this website? This will also delete all associated test definitions and reports. This action cannot be undone.
                    </p>
                </div>
                <div class="flex justify-center gap-3 mt-4">
                    <button onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">
                        Cancel
                    </button>
                    <button onclick="submitDelete()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete() {
            document.getElementById('delete-modal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('delete-modal').classList.add('hidden');
        }

        function submitDelete() {
            document.getElementById('delete-form').submit();
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('delete-modal');
            if (event.target == modal) {
                closeDeleteModal();
            }
        }
    </script>
</x-app-layout>
