<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Websites') }}
            </h2>
            <a href="{{ route('websites.create') }}" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                + Add Website
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Search and Filter Section -->
        <div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <form method="GET" action="{{ route('websites.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search Input -->
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <input 
                            type="text" 
                            name="search" 
                            id="search"
                            value="{{ request('search') }}"
                            placeholder="Search by URL..."
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select 
                            name="status" 
                            id="status"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="error" {{ request('status') === 'error' ? 'selected' : '' }}>Error</option>
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
                            <option value="url" {{ request('sort_by') === 'url' ? 'selected' : '' }}>URL</option>
                            <option value="status" {{ request('sort_by') === 'status' ? 'selected' : '' }}>Status</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
                    @if(request()->hasAny(['search', 'status', 'date_from', 'date_to', 'sort_by', 'sort_order']))
                        <a 
                            href="{{ route('websites.index') }}" 
                            class="px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition ease-in-out duration-150"
                        >
                            Clear Filters
                        </a>
                    @endif
                </div>
            </form>
        </div>

        @if($websites->isEmpty())
             <div class="flex flex-col items-center justify-center p-12 bg-white rounded-xl shadow-sm border border-gray-100 text-center">
                <div class="bg-indigo-50 p-4 rounded-full mb-4">
                    <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">No websites added yet</h3>
                <p class="text-gray-500 mt-2 mb-6 max-w-sm">Add your first website to start creating test definitions and automating your QA.</p>
                <a href="{{ route('websites.create') }}" class="text-indigo-600 hover:text-indigo-900 font-medium hover:underline">Create your first website &rarr;</a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($websites as $website)
                    @php
                        $statusColors = [
                            'active' => 'bg-green-100 text-green-800',
                            'inactive' => 'bg-gray-100 text-gray-800',
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'error' => 'bg-red-100 text-red-800',
                        ];
                        $statusColor = $statusColors[$website->status] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <div class="bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow rounded-xl border border-gray-100 flex flex-col h-full">
                        <div class="p-6 flex-grow">
                            <div class="flex justify-between items-start">
                                <h3 class="text-lg font-bold text-gray-900 truncate flex-1" title="{{ $website->url }}">
                                    {{ parse_url($website->url, PHP_URL_HOST) ?: $website->url }}
                                </h3>
                                <span class="px-2 py-1 text-xs font-semibold {{ $statusColor }} rounded-md ml-2 flex-shrink-0">
                                    {{ ucfirst($website->status) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 mt-1 truncate">{{ $website->url }}</p>
                            
                            <div class="mt-6 flex items-center justify-between text-sm text-gray-500">
                                <span>{{ $website->test_definitions_count }} Tests</span>
                                <span>{{ $website->reports_count }} Reports</span>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-end">
                            <a href="{{ route('websites.show', $website) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold text-sm">
                                Manage &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($websites->hasPages())
                <div class="mt-6">
                    {{ $websites->links() }}
                </div>
            @endif
        @endif
    </div>
</x-app-layout>
