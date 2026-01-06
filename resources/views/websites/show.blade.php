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
                <div class="flex items-center gap-3">
                    <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                        {{ $website->url }}
                    </h2>
                    @php
                        $statusColors = [
                            'active' => 'bg-green-100 text-green-800',
                            'inactive' => 'bg-gray-100 text-gray-800',
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'error' => 'bg-red-100 text-red-800',
                        ];
                        $statusColor = $statusColors[$website->status] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <span class="px-3 py-1 text-xs font-semibold {{ $statusColor }} rounded-full">
                        {{ ucfirst($website->status) }}
                    </span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('websites.edit', $website) }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition ease-in-out duration-150">
                    Edit
                </a>
                <form method="POST" action="{{ route('websites.destroy', $website) }}" class="inline" id="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="confirmDelete()" class="px-4 py-2 bg-red-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition ease-in-out duration-150">
                        Delete
                    </button>
                </form>
                <a href="{{ route('test-definitions.create', ['website_id' => $website->id]) }}" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition ease-in-out duration-150">
                    + New Test
                </a>
            </div>
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

        <!-- Website Information Card -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900">Website Information</h3>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">URL</dt>
                        <dd class="mt-1 text-sm text-gray-900 break-all">{{ $website->url }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            @php
                                $statusColors = [
                                    'active' => 'bg-green-100 text-green-800',
                                    'inactive' => 'bg-gray-100 text-gray-800',
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'error' => 'bg-red-100 text-red-800',
                                ];
                                $statusColor = $statusColors[$website->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                {{ ucfirst($website->status) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $website->created_at->format('M d, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $website->updated_at->format('M d, Y') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900">Quick Actions</h3>
            </div>
            <div class="p-6">
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('test-definitions.create', ['website_id' => $website->id]) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Create Test
                    </a>
                    <a href="{{ route('reports.index', $website) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        View Reports
                    </a>
                </div>
            </div>
        </div>

        <!-- Test Execution History -->
        @php
            $testRuns = $website->getTestRuns()->take(10);
        @endphp
        @if($testRuns->isNotEmpty())
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-bold text-gray-900">Recent Test Executions</h3>
                    <p class="text-sm text-gray-500 mt-1">Latest test run results for this website.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Test</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Result</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Executed At</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($testRuns as $testRun)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $testRun->testCase->testDefinition->description ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($testRun->result === 'pass')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                PASS
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                FAIL
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $testRun->executed_at ? $testRun->executed_at->diffForHumans() : 'N/A' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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

                                    <form method="POST" action="{{ route('test-definitions.run', $test) }}">
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
