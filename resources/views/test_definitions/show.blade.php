<x-dashboard-layout>
    <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
        <div>
            <nav class="flex items-center gap-2 mb-4">
                <a href="{{ route('test-definitions.index') }}" class="text-[10px] font-black uppercase tracking-widest text-indigo-500 hover:text-indigo-700 transition-colors">Test Definitions</a>
                <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Details</span>
            </nav>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight leading-tight max-w-2xl bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-indigo-700">
                {{ $testDefinition->description }}
            </h1>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('test-definitions.edit', $testDefinition) }}" class="px-6 py-3 bg-white border border-gray-100 rounded-xl font-bold text-xs text-gray-600 uppercase tracking-widest hover:bg-gray-50 transition-all shadow-sm">
                Edit
            </a>
            <form method="POST" action="{{ route('test-definitions.run', $testDefinition) }}" class="inline">
                @csrf
                <input type="hidden" name="async" value="1">
                <button type="submit" class="group px-8 py-3 bg-gradient-to-br from-indigo-500 to-indigo-700 text-white font-bold text-xs uppercase tracking-widest rounded-xl hover:shadow-indigo-200 transition-all shadow-xl shadow-indigo-100 flex items-center gap-2">
                    <svg class="w-4 h-4 group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <span>Run Test</span>
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-8 bg-emerald-50 border border-emerald-100 text-emerald-800 px-6 py-4 rounded-2xl flex items-center gap-3 animate-in fade-in slide-in-from-top-4">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar Info -->
        <div class="space-y-8">
            <div class="bg-white rounded-[2.5rem] border border-gray-100 p-8 shadow-sm">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6">Test Info</h3>
                <div class="space-y-6">
                    <div>
                        <div class="text-[10px] font-black text-indigo-500 uppercase tracking-widest mb-1">Target Website</div>
                        <a href="{{ route('websites.show', $testDefinition->website) }}" class="text-sm font-bold text-gray-900 hover:text-indigo-600 transition-colors break-all">
                            {{ $testDefinition->website->url }}
                        </a>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="text-[10px] font-black text-indigo-500 uppercase tracking-widest mb-1">Test Cases</div>
                            <div class="text-xl font-black text-gray-900">{{ $testDefinition->testCases->count() }}</div>
                        </div>
                        <div>
                            <div class="text-[10px] font-black text-indigo-500 uppercase tracking-widest mb-1">Created</div>
                            <div class="text-sm font-bold text-gray-900">{{ $testDefinition->created_at->format('M d, Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Last Run Status Card -->
            @php
                $lastRun = $testDefinition->testCases->first()?->testRuns?->sortByDesc('created_at')->first();
            @endphp
            @if($lastRun)
                <div class="bg-white rounded-[2.5rem] border border-gray-100 p-8 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4">
                        <div class="w-3 h-3 rounded-full {{ $lastRun->result == 'pass' ? 'bg-emerald-500 animate-pulse' : 'bg-red-500' }}"></div>
                    </div>
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6">Last Run Status</h3>
                    <div class="flex items-center gap-4 mb-4">
                        <div class="px-6 py-2 {{ $lastRun->result == 'pass' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }} rounded-2xl font-black text-sm uppercase tracking-widest border {{ $lastRun->result == 'pass' ? 'border-emerald-100' : 'border-red-100' }}">
                            {{ $lastRun->result }}
                        </div>
                    </div>
                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                        Executed {{ $lastRun->created_at->diffForHumans() }}
                    </div>
                </div>
            @endif
        </div>

        <!-- Main Content (Steps) -->
        <div class="lg:col-span-2 space-y-8">
            @if($testDefinition->testCases->isNotEmpty())
                @foreach($testDefinition->testCases as $testCase)
                    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
                        <div class="p-8 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
                            <div>
                                <h3 class="text-xl font-black text-gray-900">Execution Steps</h3>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Generated by Gemini AI</p>
                            </div>
                            <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                        </div>
                        <div class="p-8">
                            @if(!empty($testCase->steps))
                                <div class="space-y-4">
                                    @foreach($testCase->steps as $index => $step)
                                        <div class="group flex items-start gap-6 p-6 bg-gray-50/50 hover:bg-white rounded-[1.5rem] border border-transparent hover:border-indigo-100 hover:shadow-xl hover:shadow-indigo-50 transition-all duration-300">
                                            <div class="flex-shrink-0 w-10 h-10 bg-indigo-100 group-hover:bg-indigo-600 text-indigo-600 group-hover:text-white rounded-xl flex items-center justify-center text-sm font-black transition-colors">
                                                {{ $index + 1 }}
                                            </div>
                                            <div class="flex-grow">
                                                <div class="font-black text-gray-900 mb-2 uppercase tracking-wide text-xs">
                                                    {{ ucfirst(str_replace('_', ' ', $step['action'] ?? 'Unknown')) }}
                                                </div>
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                    @if(isset($step['url']))
                                                        <div class="bg-white p-3 rounded-xl border border-gray-100/50">
                                                            <div class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">URL</div>
                                                            <div class="text-xs font-bold text-indigo-600 truncate">{{ $step['url'] }}</div>
                                                        </div>
                                                    @endif
                                                    @if(isset($step['selector']))
                                                        <div class="bg-white p-3 rounded-xl border border-gray-100/50">
                                                            <div class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Selector</div>
                                                            <div class="text-xs font-bold text-gray-700 font-mono">{{ $step['selector'] }}</div>
                                                        </div>
                                                    @endif
                                                    @if(isset($step['value']))
                                                        <div class="bg-white p-3 rounded-xl border border-gray-100/50 col-span-2">
                                                            <div class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Value</div>
                                                            <div class="text-xs font-bold text-gray-700">{{ $step['value'] }}</div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <p class="text-gray-400 font-medium">No test steps generated yet.</p>
                                </div>
                            @endif

                            @if($testCase->expected_result)
                                <div class="mt-8 p-6 bg-emerald-50 rounded-[1.5rem] border border-emerald-100">
                                    <div class="flex items-center gap-3 mb-2">
                                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <div class="text-xs font-black text-emerald-900 uppercase tracking-widest">Expected Result</div>
                                    </div>
                                    <div class="text-sm font-bold text-emerald-700 leading-relaxed">{{ $testCase->expected_result }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif

            <!-- Execution History -->
            @php
                $testRuns = collect();
                foreach ($testDefinition->testCases as $testCase) {
                    $testRuns = $testRuns->merge($testCase->testRuns);
                }
                $testRuns = $testRuns->sortByDesc('executed_at')->take(5);
            @endphp

            <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-8 border-b border-gray-50 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-black text-gray-900">Recent History</h3>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Previous execution results</p>
                    </div>
                    <a href="{{ route('reports.index', ['website' => $testDefinition->website->id, 'test_definition_id' => $testDefinition->id]) }}" class="text-xs font-black text-indigo-600 uppercase tracking-[0.2em] hover:text-indigo-700 transition-colors">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-8 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Executed At</th>
                                <th class="px-8 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Status</th>
                                <th class="px-8 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Duration</th>
                                <th class="px-8 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($testRuns as $testRun)
                                <tr class="group hover:bg-gray-50/50 transition-colors">
                                    <td class="px-8 py-5">
                                        <div class="text-sm font-bold text-gray-900">{{ $testRun->executed_at ? $testRun->executed_at->format('M d, H:i') : 'N/A' }}</div>
                                        <div class="text-[10px] font-medium text-gray-400">{{ $testRun->executed_at ? $testRun->executed_at->diffForHumans() : '' }}</div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $testRun->result === 'pass' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                                            {{ $testRun->result }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 text-sm font-bold text-gray-500">
                                        {{ $testRun->duration ? number_format($testRun->duration, 2) . 's' : 'N/A' }}
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        <a href="{{ route('reports.show', $testRun) }}" class="inline-flex items-center gap-2 text-[10px] font-black text-indigo-600 uppercase tracking-widest hover:text-indigo-800 transition-all group-hover:translate-x-1">
                                            <span>Details</span>
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            @if($testRuns->isEmpty())
                                <tr>
                                    <td colspan="4" class="px-8 py-12 text-center text-gray-400 font-medium">No execution history found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>

