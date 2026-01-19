<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <nav class="flex text-sm text-gray-500 mb-1" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('test-definitions.index') }}" class="inline-flex items-center hover:text-gray-900">
                                Test Definitions
                            </a>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <span class="ml-1 text-gray-700 font-medium truncate max-w-xs">{{ Str::limit($testDefinition->description, 50) }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    {{ $testDefinition->description }}
                </h2>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('test-definitions.edit', $testDefinition) }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition">
                    Edit
                </a>
                <form method="POST" action="{{ route('test-definitions.run', $testDefinition) }}" class="inline">
                    @csrf
                    <input type="hidden" name="async" value="1">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                        Run Test
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        @if(session('success'))
            <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Test Definition Details -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900">Test Definition Details</h3>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $testDefinition->description }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Website</dt>
                        <dd class="mt-1">
                            <a href="{{ route('websites.show', $testDefinition->website) }}" class="text-indigo-600 hover:text-indigo-800">
                                {{ $testDefinition->website->url }}
                            </a>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $testDefinition->created_at->format('M d, Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $testDefinition->updated_at->format('M d, Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Test Cases</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $testDefinition->testCases->count() }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Test Steps -->
        @if($testDefinition->testCases->isNotEmpty())
            @foreach($testDefinition->testCases as $testCase)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Test Steps</h3>
                                <p class="text-sm text-gray-500 mt-1">Generated test execution steps</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        @if(!empty($testCase->steps))
                            <div class="space-y-3">
                                @foreach($testCase->steps as $index => $step)
                                    <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg border border-gray-100">
                                        <span class="flex-shrink-0 w-8 h-8 bg-indigo-100 text-indigo-700 rounded-full flex items-center justify-center text-sm font-bold">
                                            {{ $index + 1 }}
                                        </span>
                                        <div class="flex-grow">
                                            <div class="font-semibold text-gray-900 mb-1">
                                                {{ ucfirst(str_replace('_', ' ', $step['action'] ?? 'Unknown')) }}
                                            </div>
                                            <div class="text-sm text-gray-600 font-mono space-y-1">
                                                @if(isset($step['url']))
                                                    <div>URL: <span class="text-indigo-600">{{ $step['url'] }}</span></div>
                                                @endif
                                                @if(isset($step['selector']))
                                                    <div>Selector: <span class="text-indigo-600">{{ $step['selector'] }}</span></div>
                                                @endif
                                                @if(isset($step['value']))
                                                    <div>Value: <span class="text-indigo-600">{{ $step['value'] }}</span></div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">No test steps generated yet.</p>
                        @endif

                        @if($testCase->expected_result)
                            <div class="mt-4 p-4 bg-green-50 rounded-lg border border-green-100">
                                <div class="text-sm font-semibold text-green-900 mb-1">Expected Result</div>
                                <div class="text-sm text-green-700">{{ $testCase->expected_result }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif

        <!-- Test Execution History -->
        @php
            $testRuns = collect();
            foreach ($testDefinition->testCases as $testCase) {
                $testRuns = $testRuns->merge($testCase->testRuns);
            }
            $testRuns = $testRuns->sortByDesc('executed_at')->take(10);
        @endphp

        @if($testRuns->isNotEmpty())
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-bold text-gray-900">Test Execution History</h3>
                    <p class="text-sm text-gray-500 mt-1">Recent test run results</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Executed At</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Result</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($testRuns as $testRun)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $testRun->executed_at ? $testRun->executed_at->format('M d, Y H:i') : 'N/A' }}
                                        <div class="text-xs text-gray-500">{{ $testRun->executed_at ? $testRun->executed_at->diffForHumans() : '' }}</div>
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
                                        {{ $testRun->duration ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('reports.show', $testRun) }}" class="text-indigo-600 hover:text-indigo-900">View Details</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>

