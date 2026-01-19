<x-dashboard-layout>
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Test Definitions</h1>
            <p class="text-gray-500 font-medium">Define and automate your quality assurance tests.</p>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('test-definitions.create') }}" class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-2xl shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all hover:-translate-y-0.5">
                + Create New Test
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-8 bg-green-50 border border-green-100 text-green-700 px-6 py-4 rounded-2xl flex items-center gap-3">
            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Search and Filter Section -->
    <div class="mb-8 bg-white rounded-3xl shadow-sm border border-gray-100 p-6 lg:p-8">
        <form method="GET" action="{{ route('test-definitions.index') }}" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="search" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Search</label>
                    <div class="relative">
                        <input 
                            type="text" 
                            name="search" 
                            id="search"
                            value="{{ request('search') }}"
                            placeholder="Description..."
                            class="w-full pl-12 pr-4 py-3 rounded-xl border-gray-100 bg-gray-50/50 focus:border-indigo-500 focus:ring-indigo-500 transition-all font-medium text-gray-700"
                        >
                        <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>

                <div>
                    <label for="website_id" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Website</label>
                    <select 
                        name="website_id" 
                        id="website_id"
                        class="w-full rounded-xl border-gray-100 bg-gray-50/50 focus:border-indigo-500 focus:ring-indigo-500 transition-all font-medium text-gray-700"
                    >
                        <option value="">All Websites</option>
                        @foreach($websites as $website)
                            <option value="{{ $website->id }}" {{ request('website_id') == $website->id ? 'selected' : '' }}>
                                {{ parse_url($website->url, PHP_URL_HOST) ?: $website->url }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="last_result" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Status</label>
                    <select 
                        name="last_result" 
                        id="last_result"
                        class="w-full rounded-xl border-gray-100 bg-gray-50/50 focus:border-indigo-500 focus:ring-indigo-500 transition-all font-medium text-gray-700"
                    >
                        <option value="">All Results</option>
                        <option value="pass" {{ request('last_result') === 'pass' ? 'selected' : '' }}>Pass</option>
                        <option value="fail" {{ request('last_result') === 'fail' ? 'selected' : '' }}>Fail</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-4 pt-4 border-t border-gray-50">
                <button type="submit" class="px-8 py-3 bg-gray-900 text-white font-bold rounded-xl hover:bg-black transition-all">
                    Apply Filters
                </button>
                @if(request()->hasAny(['search', 'website_id', 'last_result']))
                    <a href="{{ route('test-definitions.index') }}" class="px-8 py-3 bg-white text-gray-600 font-black text-xs uppercase tracking-widest border border-gray-100 rounded-xl hover:bg-gray-50 transition-all">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    @if($testDefinitions->isEmpty())
        <div class="p-16 bg-white rounded-[2.5rem] shadow-sm border border-gray-100 text-center">
            <div class="w-20 h-20 bg-indigo-50 rounded-3xl flex items-center justify-center mx-auto mb-6 transform -rotate-3">
                <svg class="w-10 h-10 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <h3 class="text-2xl font-black text-gray-900 mb-2">No tests defined</h3>
            <p class="text-gray-500 font-medium mb-8 max-w-sm mx-auto text-lg leading-relaxed">You haven't created any test definitions yet. Start by selecting a website.</p>
            <a href="{{ route('websites.index') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-100">
                <span>Go to Websites</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>
    @else
        <div class="space-y-6">
            @foreach($testDefinitions as $testDefinition)
                @php
                    $lastRun = $testDefinition->testCases->first()?->testRuns?->first();
                @endphp
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                        <div class="flex-grow min-w-0">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="px-4 py-1.5 bg-indigo-50 rounded-full text-[10px] font-black uppercase tracking-widest text-indigo-600">
                                    {{ parse_url($testDefinition->website->url, PHP_URL_HOST) }}
                                </div>
                                @if($lastRun)
                                    <div class="flex items-center gap-2 px-3 py-1 rounded-full {{ $lastRun->result == 'pass' ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $lastRun->result == 'pass' ? 'bg-green-400' : 'bg-red-400' }}"></span>
                                        <span class="text-[10px] font-black uppercase tracking-widest">{{ $lastRun->result }}</span>
                                    </div>
                                @endif
                            </div>
                            <h3 class="text-xl font-black text-gray-900 mb-2 truncate group-hover:text-indigo-600 transition-colors">
                                {{ $testDefinition->description }}
                            </h3>
                            <div class="flex items-center gap-4 text-xs font-bold text-gray-400 uppercase tracking-widest">
                                <span>{{ $testDefinition->testCases->count() }} Test Cases</span>
                                <span>•</span>
                                <span>Created {{ $testDefinition->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <a href="{{ route('test-definitions.show', $testDefinition) }}" class="px-5 py-2.5 bg-gray-50 text-gray-600 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-gray-100 transition-all">
                                Details
                            </a>
                            <a href="{{ route('test-definitions.edit', $testDefinition) }}" class="px-5 py-2.5 bg-gray-50 text-gray-600 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-gray-100 transition-all">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('test-definitions.run', $testDefinition) }}" class="inline">
                                @csrf
                                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100">
                                    Run Test
                                </button>
                            </form>
                            <form method="POST" action="{{ route('test-definitions.destroy', $testDefinition) }}" class="inline" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2.5 bg-red-50 text-red-500 rounded-xl hover:bg-red-100 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($testDefinitions->hasPages())
            <div class="mt-12">
                {{ $testDefinitions->links() }}
            </div>
        @endif
    @endif
</x-dashboard-layout>
