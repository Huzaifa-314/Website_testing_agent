<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Test Run Details') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.test-runs.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
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

        <!-- Test Run Information -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Test Run Information</h3>
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'running' => 'bg-blue-100 text-blue-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'failed' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$testRun->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($testRun->status) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Result</dt>
                        <dd class="mt-1">
                            @if($testRun->result)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $testRun->result === 'pass' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ strtoupper($testRun->result) }}
                                </span>
                            @else
                                <span class="text-sm text-gray-400">N/A</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Executed At</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $testRun->executed_at ? $testRun->executed_at->format('M d, Y H:i:s') : 'N/A' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Progress</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @if($testRun->total_steps > 0)
                                {{ $testRun->current_step }} / {{ $testRun->total_steps }}
                            @else
                                N/A
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Website</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <a href="{{ route('admin.websites.show', $testRun->testCase->testDefinition->website) }}" class="text-indigo-600 hover:text-indigo-900">
                                {{ $testRun->testCase->testDefinition->website->url }}
                            </a>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Test Definition</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <a href="{{ route('admin.test-definitions.show', $testRun->testCase->testDefinition) }}" class="text-indigo-600 hover:text-indigo-900">
                                {{ Str::limit($testRun->testCase->testDefinition->description, 50) }}
                            </a>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">User</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <a href="{{ route('admin.users.show', $testRun->testCase->testDefinition->website->user) }}" class="text-indigo-600 hover:text-indigo-900">
                                {{ $testRun->testCase->testDefinition->website->user->name }}
                            </a>
                            <div class="text-xs text-gray-500">{{ $testRun->testCase->testDefinition->website->user->email }}</div>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Test Case Information -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Test Case Information</h3>
                <dl class="grid grid-cols-1 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Expected Result</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $testRun->testCase->expected_result }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Steps</dt>
                        <dd class="mt-1">
                            <ol class="list-decimal list-inside text-sm text-gray-700 space-y-1">
                                @foreach($testRun->testCase->steps as $index => $step)
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
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Logs -->
        @if($testRun->logs && count($testRun->logs) > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Execution Logs</h3>
                    <div class="bg-gray-900 text-green-400 p-4 rounded-lg font-mono text-sm overflow-x-auto">
                        @foreach($testRun->logs as $log)
                            <div class="mb-1">{{ $log }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-admin-layout>

