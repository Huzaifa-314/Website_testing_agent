<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                 <nav class="flex text-sm text-gray-500 mb-1" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('websites.index') }}" class="inline-flex items-center hover:text-gray-900">
                                My Websites
                            </a>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <span class="ml-1 text-gray-700 font-medium truncate max-w-xs">{{ $website->url }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    {{ $website->url }}
                </h2>
            </div>
            <a href="{{ route('test-definitions.create', ['website_id' => $website->id]) }}" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition ease-in-out duration-150">
                + New Test
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6 border border-gray-100">
                <div class="text-gray-500 text-sm font-medium uppercase tracking-wider">Test Definitions</div>
                <div class="font-bold text-3xl mt-2 text-indigo-600">{{ $website->testDefinitions->count() }}</div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6 border border-gray-100">
                <div class="text-gray-500 text-sm font-medium uppercase tracking-wider">Total Executions</div>
                <div class="font-bold text-3xl mt-2 text-purple-600">{{ $website->reports->count() }}</div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6 border border-gray-100 flex flex-col justify-between">
                <div>
                     <div class="text-gray-500 text-sm font-medium uppercase tracking-wider">Reports</div>
                    <div class="font-bold text-3xl mt-2 text-gray-800">{{ $website->reports->count() }}</div>
                </div>
                 <div class="text-right mt-2">
                    <a href="{{ route('reports.index', $website) }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">View History &rarr;</a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-indigo-50 border border-indigo-100 text-indigo-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Test Definitions List -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900">Test Definitions</h3>
                <p class="text-sm text-gray-500">Manage the automated tests for this website.</p>
            </div>
            
             @if($website->testDefinitions->isEmpty())
                <div class="p-12 text-center">
                    <p class="text-gray-500 mb-4">No tests defined yet.</p>
                    <a href="{{ route('test-definitions.create', ['website_id' => $website->id]) }}" class="text-indigo-600 font-semibold hover:underline">Create your first test</a>
                </div>
            @else
                <ul class="divide-y divide-gray-100">
                    @foreach($website->testDefinitions as $test)
                        <li class="p-6 hover:bg-gray-50 transition duration-150">
                            <div class="flex items-center justify-between">
                                <div class="flex-grow">
                                    <h4 class="text-lg font-semibold text-gray-900">{{ $test->description }}</h4>
                                    <div class="mt-1 flex items-center gap-4 text-sm text-gray-500">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $test->test_scope }}
                                        </span>
                                        <span>Created {{ $test->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-600 max-w-2xl bg-gray-50 p-2 rounded border border-gray-100 font-mono">
                                        {{ Str::limit(json_encode($test->testCases->first()->steps ?? []), 100) }}
                                    </p>
                                </div>
                                <div class="ml-4 flex-shrink-0 flex items-center gap-4">
                                     @php
                                        $lastRun = $test->testCases->first()?->testRuns?->last();
                                    @endphp
                                    
                                    @if($lastRun)
                                        <div class="text-right mr-4">
                                             <div class="text-xs text-gray-400">Last Run</div>
                                            <div class="font-semibold {{ $lastRun->result == 'pass' ? 'text-green-600' : 'text-red-600' }}">
                                                {{ strtoupper($lastRun->result) }}
                                            </div>
                                        </div>
                                    @endif

                                    <form method="POST" action="{{ route('test-definitions.run', $test->id) }}">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                            <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Run Now
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-app-layout>
