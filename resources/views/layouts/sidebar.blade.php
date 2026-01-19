<aside class="w-full md:w-64 flex-shrink-0 hidden md:block">
    <nav class="sticky top-32 space-y-8 glass-effect p-6 rounded-3xl border border-gray-100 shadow-sm">
        <div class="space-y-4">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest px-2">Main Menu</h3>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('dashboard') ? 'bg-purple-50 text-purple-600 font-bold border border-purple-100' : 'text-gray-600 hover:bg-gray-50 transition-colors' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('reports.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('reports.*') ? 'bg-purple-50 text-purple-600 font-bold border border-purple-100' : 'text-gray-600 hover:bg-gray-50 transition-colors' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        <span>Analytics</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('websites.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('websites.*') ? 'bg-purple-50 text-purple-600 font-bold border border-purple-100' : 'text-gray-600 hover:bg-gray-50 transition-colors' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                        <span>Websites</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('test-definitions.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('test-definitions.*') ? 'bg-purple-50 text-purple-600 font-bold border border-purple-100' : 'text-gray-600 hover:bg-gray-50 transition-colors' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        <span>Test Definitions</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="space-y-4">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest px-2">Support</h3>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('docs') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-gray-600 hover:bg-gray-50 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        <span>Documentation</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</aside>
