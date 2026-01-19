<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Test Definition Details') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.test-definitions.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        @if(session('success'))
            <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Test Definition Information -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Test Definition Information</h3>
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $testDefinition->description }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Website</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <a href="{{ route('admin.websites.show', $testDefinition->website) }}" class="text-indigo-600 hover:text-indigo-900">
                                {{ $testDefinition->website->url }}
                            </a>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Owner</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <a href="{{ route('admin.users.show', $testDefinition->website->user) }}" class="text-indigo-600 hover:text-indigo-900">
                                {{ $testDefinition->website->user->name }}
                            </a>
                            <div class="text-xs text-gray-500">{{ $testDefinition->website->user->email }}</div>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created At</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $testDefinition->created_at->format('M d, Y H:i:s') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-500 text-sm">Test Cases</div>
                <div class="font-bold text-2xl text-indigo-600">{{ $stats['test_cases_count'] }}</div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-500 text-sm">Total Test Runs</div>
                <div class="font-bold text-2xl text-purple-600">{{ $stats['test_runs_count'] }}</div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-500 text-sm">Successful Runs</div>
                <div class="font-bold text-2xl text-green-600">{{ $stats['successful_runs'] }}</div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-500 text-sm">Failed Runs</div>
                <div class="font-bold text-2xl text-red-600">{{ $stats['failed_runs'] }}</div>
            </div>
        </div>

        <!-- Test Cases -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Test Cases</h3>
                @if($testDefinition->testCases->isEmpty())
                    <p class="text-gray-500">No test cases found.</p>
                @else
                    <div class="space-y-4">
                        @foreach($testDefinition->testCases as $testCase)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h4 class="font-medium text-gray-900">Test Case #{{ $testCase->id }}</h4>
                                        <p class="text-sm text-gray-500 mt-1">{{ $testCase->expected_result }}</p>
                                    </div>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $testCase->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($testCase->status) }}
                                    </span>
                                </div>
                                <div class="mt-2">
                                    <p class="text-xs font-medium text-gray-500 mb-1">Steps:</p>
                                    <ol class="list-decimal list-inside text-sm text-gray-700 space-y-1">
                                        @foreach($testCase->steps as $index => $step)
                                            <li class="mb-1">
                                                <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $step['action'] ?? 'Unknown')) }}</span>
                                                @if(isset($step['url']))
                                                    <span class="text-gray-600"> - URL: <span class="text-indigo-600">{{ $step['url'] }}</span></span>
                                                @endif
                                                @if(isset($step['selector']))
                                                    <span class="text-gray-600"> - Selector: <span class="text-indigo-600">{{ $step['selector'] }}</span></span>
                                                @endif
                                                @if(isset($step['value']))
                                                    <span class="text-gray-600"> - Value: <span class="text-indigo-600">{{ $step['value'] }}</span></span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ol>
                                </div>
                                <div class="mt-3 text-xs text-gray-500">
                                    Test Runs: {{ $testCase->testRuns->count() }} | 
                                    Passed: {{ $testCase->testRuns->where('result', 'pass')->count() }} | 
                                    Failed: {{ $testCase->testRuns->where('result', 'fail')->count() }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>

