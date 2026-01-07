<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Test Definitions Management') }}
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
                <form method="GET" action="{{ route('admin.test-definitions.index') }}" class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <x-text-input 
                            id="search" 
                            class="block w-full" 
                            type="text" 
                            name="search" 
                            :value="request('search')" 
                            placeholder="Search by description, scope, URL, or user..." 
                        />
                    </div>
                    <div>
                        <select name="website_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">All Websites</option>
                            @foreach($websites as $website)
                                <option value="{{ $website->id }}" {{ request('website_id') == $website->id ? 'selected' : '' }}>
                                    {{ Str::limit($website->url, 40) }} ({{ $website->user->name }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select name="test_scope" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">All Scopes</option>
                            @foreach(['functional', 'performance', 'security', 'usability', 'compatibility'] as $scope)
                                <option value="{{ $scope }}" {{ request('test_scope') === $scope ? 'selected' : '' }}>
                                    {{ ucfirst($scope) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <x-primary-button type="submit">Filter</x-primary-button>
                    @if(request('search') || request('website_id') || request('test_scope'))
                        <a href="{{ route('admin.test-definitions.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                            Clear
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Bulk Actions -->
        <div x-show="selectedIds.length > 0" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
            <form method="POST" action="{{ route('admin.test-definitions.bulk-delete') }}" onsubmit="return confirm('Are you sure you want to delete the selected test definitions?');">
                @csrf
                <input type="hidden" name="ids" :value="JSON.stringify(selectedIds)">
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    Delete Selected (<span x-text="selectedIds.length"></span>)
                </button>
            </form>
        </div>

        <!-- Test Definitions Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left">
                                <input type="checkbox" @change="selectedIds = $el.checked ? [{{ $testDefinitions->pluck('id')->join(',') }}] : []" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Website</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Test Scope</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Test Cases</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($testDefinitions as $testDefinition)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" :value="{{ $testDefinition->id }}" x-model="selectedIds" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ Str::limit($testDefinition->description, 60) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <a href="{{ route('admin.websites.show', $testDefinition->website) }}" class="text-indigo-600 hover:text-indigo-900">
                                            {{ Str::limit($testDefinition->website->url, 40) }}
                                        </a>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">{{ $testDefinition->website->user->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $testDefinition->test_scope }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $testDefinition->testCases->count() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $testDefinition->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.test-definitions.show', $testDefinition) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                        <form method="POST" action="{{ route('admin.test-definitions.destroy', $testDefinition) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this test definition?');">
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
                                    No test definitions found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($testDefinitions->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $testDefinitions->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>

