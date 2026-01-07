<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Test Definitions') }}
            </h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('websites.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition ease-in-out duration-150">
                    My Websites
                </a>
                <a href="{{ route('test-definitions.create') }}" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition ease-in-out duration-150">
                    + New Test Definition
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6">
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Search and Filter Section -->
        <div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <form method="GET" action="{{ route('test-definitions.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search Input -->
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <input 
                            type="text" 
                            name="search" 
                            id="search"
                            value="{{ request('search') }}"
                            placeholder="Search by description, scope, or website URL..."
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                    </div>

                    <!-- Website Filter -->
                    <div>
                        <label for="website_id" class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                        <select 
                            name="website_id" 
                            id="website_id"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="">All Websites</option>
                            @foreach($websites as $website)
                                <option value="{{ $website->id }}" {{ request('website_id') == $website->id ? 'selected' : '' }}>
                                    {{ parse_url($website->url, PHP_URL_HOST) ?: $website->url }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Test Scope Filter -->
                    <div>
                        <label for="test_scope" class="block text-sm font-medium text-gray-700 mb-2">Test Scope</label>
                        <select 
                            name="test_scope" 
                            id="test_scope"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="">All Scopes</option>
                            <option value="smoke" {{ request('test_scope') === 'smoke' ? 'selected' : '' }}>Smoke</option>
                            <option value="regression" {{ request('test_scope') === 'regression' ? 'selected' : '' }}>Regression</option>
                            <option value="integration" {{ request('test_scope') === 'integration' ? 'selected' : '' }}>Integration</option>
                            <option value="e2e" {{ request('test_scope') === 'e2e' ? 'selected' : '' }}>E2E</option>
                            <option value="performance" {{ request('test_scope') === 'performance' ? 'selected' : '' }}>Performance</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <!-- Date From -->
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                        <input 
                            type="date" 
                            name="date_from" 
                            id="date_from"
                            value="{{ request('date_from') }}"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                    </div>

                    <!-- Date To -->
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                        <input 
                            type="date" 
                            name="date_to" 
                            id="date_to"
                            value="{{ request('date_to') }}"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                    </div>

                    <!-- Last Result Filter -->
                    <div>
                        <label for="last_result" class="block text-sm font-medium text-gray-700 mb-2">Last Result</label>
                        <select 
                            name="last_result" 
                            id="last_result"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="">All Results</option>
                            <option value="pass" {{ request('last_result') === 'pass' ? 'selected' : '' }}>Pass</option>
                            <option value="fail" {{ request('last_result') === 'fail' ? 'selected' : '' }}>Fail</option>
                        </select>
                    </div>

                    <!-- Sort By -->
                    <div>
                        <label for="sort_by" class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                        <select 
                            name="sort_by" 
                            id="sort_by"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="created_at" {{ request('sort_by', 'created_at') === 'created_at' ? 'selected' : '' }}>Date Created</option>
                            <option value="updated_at" {{ request('sort_by') === 'updated_at' ? 'selected' : '' }}>Last Updated</option>
                            <option value="description" {{ request('sort_by') === 'description' ? 'selected' : '' }}>Description</option>
                            <option value="test_scope" {{ request('sort_by') === 'test_scope' ? 'selected' : '' }}>Test Scope</option>
                        </select>
                    </div>

                    <!-- Sort Order -->
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Order</label>
                        <select 
                            name="sort_order" 
                            id="sort_order"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="desc" {{ request('sort_order', 'desc') === 'desc' ? 'selected' : '' }}>Descending</option>
                            <option value="asc" {{ request('sort_order') === 'asc' ? 'selected' : '' }}>Ascending</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button 
                        type="submit" 
                        class="px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition ease-in-out duration-150"
                    >
                        Apply Filters
                    </button>
                    @if(request()->hasAny(['search', 'website_id', 'test_scope', 'date_from', 'date_to', 'last_result', 'sort_by', 'sort_order']))
                        <a 
                            href="{{ route('test-definitions.index') }}" 
                            class="px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition ease-in-out duration-150"
                        >
                            Clear Filters
                        </a>
                    @endif
                </div>
            </form>
        </div>

        @if($testDefinitions->isEmpty())
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No test definitions</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new test definition.</p>
                    <div class="mt-6">
                        <a href="{{ route('websites.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition ease-in-out duration-150">
                            Select a Website
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-bold text-gray-900">All Test Definitions</h3>
                    <p class="text-sm text-gray-500 mt-1">Manage your automated test definitions across all websites.</p>
                </div>
                
                <div class="divide-y divide-gray-100">
                    @foreach($testDefinitions as $testDefinition)
                        <div class="p-6 hover:bg-gray-50 transition duration-150">
                            <div class="flex items-start justify-between">
                                <div class="flex-grow">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h4 class="text-lg font-semibold text-gray-900">{{ $testDefinition->description }}</h4>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ ucfirst($testDefinition->test_scope) }}
                                        </span>
                                    </div>
                                    
                                    <div class="flex items-center gap-4 text-sm text-gray-500 mb-3">
                                        <a href="{{ route('websites.show', $testDefinition->website) }}" class="hover:text-indigo-600 font-medium">
                                            {{ parse_url($testDefinition->website->url, PHP_URL_HOST) ?: $testDefinition->website->url }}
                                        </a>
                                        <span>•</span>
                                        <span>Created {{ $testDefinition->created_at->diffForHumans() }}</span>
                                        @if($testDefinition->testCases->isNotEmpty())
                                            <span>•</span>
                                            <span>{{ $testDefinition->testCases->count() }} test case(s)</span>
                                        @endif
                                    </div>

                                    @php
                                        $lastRun = $testDefinition->testCases->first()?->testRuns?->first();
                                        $steps = $testDefinition->testCases->first()?->steps ?? [];
                                    @endphp

                                    @if(!empty($steps))
                                        <div class="mt-3 bg-gray-50 p-3 rounded border border-gray-100">
                                            <div class="text-xs font-semibold text-gray-500 uppercase mb-2">Generated Steps Preview</div>
                                            <div class="text-sm text-gray-700 font-mono">
                                                @foreach(array_slice($steps, 0, 3) as $step)
                                                    <div class="mb-1">• {{ $step['action'] ?? 'N/A' }} @if(isset($step['selector']))→ {{ $step['selector'] }} @endif</div>
                                                @endforeach
                                                @if(count($steps) > 3)
                                                    <div class="text-gray-400 italic">... and {{ count($steps) - 3 }} more step(s)</div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    @if($lastRun)
                                        <div class="mt-3 flex items-center gap-2">
                                            <span class="text-xs text-gray-500">Last Run:</span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $lastRun->result == 'pass' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ strtoupper($lastRun->result) }}
                                            </span>
                                            <span class="text-xs text-gray-400">{{ $lastRun->executed_at ? $lastRun->executed_at->diffForHumans() : 'N/A' }}</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="ml-4 flex-shrink-0 flex items-center gap-2">
                                    <a href="{{ route('test-definitions.show', $testDefinition) }}" class="inline-flex items-center px-3 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition">
                                        View
                                    </a>
                                    <a href="{{ route('test-definitions.edit', $testDefinition) }}" class="inline-flex items-center px-3 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('test-definitions.destroy', $testDefinition) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this test definition?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition">
                                            Delete
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('test-definitions.run', $testDefinition) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-3 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                                            Run
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($testDefinitions->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $testDefinitions->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>
</x-app-layout>

