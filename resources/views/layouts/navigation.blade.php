<nav x-data="{ open: false }" class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-100/50">
    <div class="max-w-7xl mx-auto px-6 md:px-12">
        <div class="flex justify-between items-center py-6">
            <!-- Left: Logo -->
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.svg') }}" alt="Klydos Logo" class="w-10 h-10">
                    <span class="text-2xl font-bold tracking-tight bg-gradient-to-r from-pink-500 to-purple-500 bg-clip-text text-transparent">Klydos</span>
                </a>
            </div>

            <!-- Middle: Navigation Links -->
            <div class="hidden md:flex items-center gap-10">
                @auth
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'text-purple-600 font-bold' : 'text-gray-700 font-semibold hover:text-purple-600' }} transition-colors text-sm uppercase tracking-wider">
                        {{ __('Dashboard') }}
                    </a>
                @endauth
                <a href="{{ route('pricing') }}" class="{{ request()->routeIs('pricing') ? 'text-purple-600 font-bold' : 'text-gray-700 font-semibold hover:text-purple-600' }} transition-colors text-sm uppercase tracking-wider">
                    {{ __('Pricing') }}
                </a>
                <a href="{{ route('docs') }}" class="{{ request()->routeIs('docs') ? 'text-purple-600 font-bold' : 'text-gray-700 font-semibold hover:text-purple-600' }} transition-colors text-sm uppercase tracking-wider">
                    {{ __('Docs') }}
                </a>
                @auth
                    @if(!Auth::user()->isAdmin())
                        <a href="{{ route('reports.dashboard') }}" class="{{ request()->routeIs('reports.dashboard') ? 'text-purple-600 font-bold' : 'text-gray-700 font-semibold hover:text-purple-600' }} transition-colors text-sm uppercase tracking-wider">
                            {{ __('Reports') }}
                        </a>
                    @endif
                @endauth
            </div>

            <!-- Right: Auth / User -->
            <div class="hidden md:flex items-center gap-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center gap-2 px-4 py-2 text-sm font-bold text-white bg-gradient-to-r from-pink-500 to-purple-500 border border-transparent rounded-full transition-all hover:shadow-md">
                                {{ Auth::user()->name }}
                                <svg class="fill-current h-4 w-4 opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            @if(Auth::user()->isAdmin())
                                <x-dropdown-link :href="route('admin.dashboard')">
                                    {{ __('Admin Panel') }}
                                </x-dropdown-link>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="font-semibold text-gray-700 hover:text-purple-600 transition-colors">Log in</a>
                    <a href="{{ route('register') }}" class="px-6 py-3 bg-gradient-to-r from-pink-500 to-purple-500 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all hover:-translate-y-0.5">
                        Get Started
                    </a>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <div class="flex items-center md:hidden">
                <button @click="open = ! open" class="p-2 rounded-xl text-gray-400 hover:text-gray-500 hover:bg-gray-100 transition-colors">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <div x-show="open" x-cloak class="md:hidden bg-white border-t border-gray-100 shadow-2xl overflow-hidden">
        <div class="px-6 py-8 space-y-6">
            @auth
                <a href="{{ route('dashboard') }}" class="block text-lg font-bold text-gray-800">Dashboard</a>
            @endauth
            <a href="{{ route('pricing') }}" class="block text-lg font-bold text-gray-800">Pricing</a>
            <a href="{{ route('docs') }}" class="block text-lg font-bold text-gray-800">Docs</a>
            @auth
                <a href="{{ route('reports.dashboard') }}" class="block text-lg font-bold text-gray-800">Reports</a>
                <div class="pt-6 border-t border-gray-100">
                    <div class="text-xs uppercase tracking-widest text-gray-400 mb-4">Account</div>
                    <a href="{{ route('profile.edit') }}" class="block text-gray-600 mb-4">{{ Auth::user()->name }}</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-red-500 font-bold">Log Out</button>
                    </form>
                </div>
            @else
                <div class="pt-6 border-t border-gray-100 space-y-4">
                    <a href="{{ route('login') }}" class="block text-center py-3 text-gray-600 font-bold">Log in</a>
                    <a href="{{ route('register') }}" class="block text-center py-4 bg-gradient-to-r from-pink-500 to-purple-500 text-white font-bold rounded-2xl shadow-lg">Get Started</a>
                </div>
            @endauth
        </div>
    </div>
</nav>
