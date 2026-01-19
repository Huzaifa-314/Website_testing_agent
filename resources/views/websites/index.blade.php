<x-dashboard-layout>
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">My Websites</h1>
            <p class="text-gray-500 font-medium">Manage and monitor your connected websites.</p>
        </div>
        <a href="{{ route('websites.create') }}" class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-2xl shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all hover:-translate-y-0.5">
            + Add Website
        </a>
    </div>

    @if(session('success'))
        <div class="mb-8 bg-green-50 border border-green-100 text-green-700 px-6 py-4 rounded-2xl flex items-center gap-3">
            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Search and Filter Section -->
    <div class="mb-8 bg-white rounded-3xl shadow-sm border border-gray-100 p-6 lg:p-8">
        <form method="GET" action="{{ route('websites.index') }}" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="md:col-span-2">
                    <label for="search" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Search</label>
                    <div class="relative">
                        <input 
                            type="text" 
                            name="search" 
                            id="search"
                            value="{{ request('search') }}"
                            placeholder="Enter URL..."
                            class="w-full pl-12 pr-4 py-3 rounded-xl border-gray-100 bg-gray-50/50 focus:border-indigo-500 focus:ring-indigo-500 transition-all font-medium text-gray-700"
                        >
                        <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>

                <div>
                    <label for="status" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Status</label>
                    <select 
                        name="status" 
                        id="status"
                        class="w-full rounded-xl border-gray-100 bg-gray-50/50 focus:border-indigo-500 focus:ring-indigo-500 transition-all font-medium text-gray-700"
                    >
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="error" {{ request('status') === 'error' ? 'selected' : '' }}>Error</option>
                    </select>
                </div>

                <div>
                    <label for="sort_by" class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Sort By</label>
                    <select 
                        name="sort_by" 
                        id="sort_by"
                        class="w-full rounded-xl border-gray-100 bg-gray-50/50 focus:border-indigo-500 focus:ring-indigo-500 transition-all font-medium text-gray-700"
                    >
                        <option value="created_at" {{ request('sort_by', 'created_at') === 'created_at' ? 'selected' : '' }}>Recent First</option>
                        <option value="url" {{ request('sort_by') === 'url' ? 'selected' : '' }}>URL</option>
                        <option value="status" {{ request('sort_by') === 'status' ? 'selected' : '' }}>Status</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-4 pt-4 border-t border-gray-50">
                <button type="submit" class="px-8 py-3 bg-gray-900 text-white font-bold rounded-xl hover:bg-black transition-all">
                    Apply Filters
                </button>
                @if(request()->hasAny(['search', 'status', 'sort_by']))
                    <a href="{{ route('websites.index') }}" class="px-8 py-3 bg-white text-gray-600 font-black text-xs uppercase tracking-widest border border-gray-100 rounded-xl hover:bg-gray-50 transition-all">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    @if($websites->isEmpty())
         <div class="p-16 bg-white rounded-[2.5rem] shadow-sm border border-gray-100 text-center">
            <div class="w-20 h-20 bg-indigo-50 rounded-3xl flex items-center justify-center mx-auto mb-6 transform rotate-3">
                <svg class="w-10 h-10 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
            </div>
            <h3 class="text-2xl font-black text-gray-900 mb-2">No websites found</h3>
            <p class="text-gray-500 font-medium mb-8 max-w-sm mx-auto text-lg leading-relaxed">Start your automation journey by adding your first website to the platform.</p>
            <a href="{{ route('websites.create') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-100">
                <span>Add Website</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-8">
            @foreach($websites as $website)
                @php
                    $statusColors = [
                        'active' => ['bg' => 'bg-green-50', 'text' => 'text-green-600', 'dot' => 'bg-green-400'],
                        'inactive' => ['bg' => 'bg-gray-50', 'text' => 'text-gray-600', 'dot' => 'bg-gray-400'],
                        'pending' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-600', 'dot' => 'bg-yellow-400'],
                        'error' => ['bg' => 'bg-red-50', 'text' => 'text-red-600', 'dot' => 'bg-red-400'],
                    ];
                    $color = $statusColors[$website->status] ?? $statusColors['inactive'];
                @endphp
                <div class="group bg-white p-8 rounded-[2.5rem] shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 border border-gray-100 flex flex-col h-full relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-8">
                         <div class="flex items-center gap-2 px-3 py-1 rounded-full {{ $color['bg'] }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $color['dot'] }}"></span>
                            <span class="text-[10px] font-black uppercase tracking-widest {{ $color['text'] }}">{{ $website->status }}</span>
                        </div>
                    </div>

                    <div class="mb-8">
                        <div class="w-16 h-16 rounded-3xl bg-gray-50 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-500">
                            <span class="text-2xl font-black text-indigo-600">
                                {{ strtoupper(substr(parse_url($website->url, PHP_URL_HOST) ?? $website->url, 0, 1)) }}
                            </span>
                        </div>
                        <h3 class="text-2xl font-black text-gray-900 truncate group-hover:text-indigo-600 transition-colors" title="{{ $website->url }}">
                            {{ parse_url($website->url, PHP_URL_HOST) ?: $website->url }}
                        </h3>
                        <p class="text-sm text-gray-400 font-medium mt-2 truncate">{{ $website->url }}</p>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mt-auto">
                        <div class="p-4 rounded-3xl bg-gray-50 border border-gray-100">
                            <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ $website->test_definitions_count }}</div>
                            <div class="text-sm font-bold text-gray-900 uppercase">Tests</div>
                        </div>
                        <div class="p-4 rounded-3xl bg-gray-50 border border-gray-100">
                            <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ $website->reports_count ?? 0 }}</div>
                            <div class="text-sm font-bold text-gray-900 uppercase">Reports</div>
                        </div>
                    </div>

                    <div class="mt-8 pt-8 border-t border-gray-50 flex items-center justify-between">
                         <a href="{{ route('websites.show', $website) }}" class="flex items-center gap-2 text-indigo-600 font-black text-xs uppercase tracking-[0.2em] group/link">
                            View Dashboard
                            <svg class="w-4 h-4 group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                        <div class="text-[10px] font-bold text-gray-300">Updated {{ $website->updated_at->diffForHumans() }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($websites->hasPages())
            <div class="mt-12">
                {{ $websites->links() }}
            </div>
        @endif
    @endif
</x-dashboard-layout>
