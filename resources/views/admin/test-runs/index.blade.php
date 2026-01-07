<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Test Runs Management') }}
            </h2>
        </div>
    </x-slot>

    <div class="space-y-6" x-data="{ selectedIds: [] }">
        @if(session('success'))
            <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error') || $errors->any())
            <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
                @if(session('error'))
                    <span class="block sm:inline">{{ session('error') }}</span>
                @endif
                @if($errors->any())
                    <ul class="mt-2 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endif

        <!-- Search and Filter -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <form method="GET" action="{{ route('admin.test-runs.index') }}" class="flex flex-col gap-4">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <x-text-input 
                                id="search" 
                                class="block w-full" 
                                type="text" 
                                name="search" 
                                :value="request('search')" 
                                placeholder="Search by URL or user..." 
                            />
                        </div>
                        <div>
                            <select name="result" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">All Results</option>
                                <option value="pass" {{ request('result') === 'pass' ? 'selected' : '' }}>Pass</option>
                                <option value="fail" {{ request('result') === 'fail' ? 'selected' : '' }}>Fail</option>
                            </select>
                        </div>
                        <div>
                            <select name="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="running" {{ request('status') === 'running' ? 'selected' : '' }}>Running</option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>
                        <div>
                            <select name="website_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">All Websites</option>
                                @foreach($websites as $website)
                                    <option value="{{ $website->id }}" {{ request('website_id') == $website->id ? 'selected' : '' }}>
                                        {{ Str::limit($website->url, 30) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div>
                            <x-text-input 
                                id="date_from" 
                                class="block" 
                                type="date" 
                                name="date_from" 
                                :value="request('date_from')" 
                                placeholder="Date From" 
                            />
                        </div>
                        <div>
                            <x-text-input 
                                id="date_to" 
                                class="block" 
                                type="date" 
                                name="date_to" 
                                :value="request('date_to')" 
                                placeholder="Date To" 
                            />
                        </div>
                        <x-primary-button type="submit">Filter</x-primary-button>
                        @if(request('search') || request('result') || request('status') || request('website_id') || request('date_from') || request('date_to'))
                            <a href="{{ route('admin.test-runs.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Bulk Actions -->
        <div x-show="selectedIds.length > 0" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
            <form method="POST" action="{{ route('admin.test-runs.bulk-delete') }}" onsubmit="return confirm('Are you sure you want to delete the selected test runs?');">
                @csrf
                <input type="hidden" name="ids" :value="JSON.stringify(selectedIds)">
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    Delete Selected (<span x-text="selectedIds.length"></span>)
                </button>
            </form>
        </div>

        <!-- Test Runs Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left">
                                <input type="checkbox" @change="selectedIds = $el.checked ? [{{ $testRuns->pluck('id')->join(',') }}] : []" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Executed At</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Website</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Result</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($testRuns as $testRun)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" :value="{{ $testRun->id }}" x-model="selectedIds" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $testRun->executed_at ? $testRun->executed_at->format('M d, Y H:i:s') : 'N/A' }}</div>
                                    <div class="text-xs text-gray-500">{{ $testRun->executed_at ? $testRun->executed_at->diffForHumans() : '' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <a href="{{ route('admin.websites.show', $testRun->testCase->testDefinition->website) }}" class="text-indigo-600 hover:text-indigo-900">
                                            {{ Str::limit($testRun->testCase->testDefinition->website->url, 40) }}
                                        </a>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">{{ $testRun->testCase->testDefinition->website->user->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
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
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($testRun->result)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $testRun->result === 'pass' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ strtoupper($testRun->result) }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($testRun->total_steps > 0)
                                        {{ $testRun->current_step }} / {{ $testRun->total_steps }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.test-runs.show', $testRun) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                        <form method="POST" action="{{ route('admin.test-runs.destroy', $testRun) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this test run?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No test runs found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($testRuns->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $testRuns->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>


