<aside class="w-80 bg-white border-r border-gray-100 flex flex-col flex-shrink-0 relative z-20 h-full">
    <div class="flex-1 overflow-y-auto custom-scrollbar">
        <!-- Navigation Menu -->
        <div class="p-6">
            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Main Menu</h3>
            <nav class="space-y-1">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:bg-gray-50 hover:text-indigo-600' }} transition-all duration-200 group">
                    <svg class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="text-sm font-bold">Dashboard</span>
                </a>

                <a href="{{ route('websites.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('websites.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:bg-gray-50 hover:text-indigo-600' }} transition-all duration-200 group">
                    <svg class="w-5 h-5 {{ request()->routeIs('websites.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                    </svg>
                    <span class="text-sm font-bold">Websites</span>
                </a>

                <a href="{{ route('test-definitions.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('test-definitions.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:bg-gray-50 hover:text-indigo-600' }} transition-all duration-200 group">
                    <svg class="w-5 h-5 {{ request()->routeIs('test-definitions.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <span class="text-sm font-bold">Test Definitions</span>
                </a>

                <a href="{{ route('reports.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('reports.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:bg-gray-50 hover:text-indigo-600' }} transition-all duration-200 group">
                    <svg class="w-5 h-5 {{ request()->routeIs('reports.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="text-sm font-bold">Reports</span>
                </a>
            </nav>
        </div>
    </div>
</aside>
