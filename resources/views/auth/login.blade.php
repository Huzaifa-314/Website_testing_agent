<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-10 text-center">
        <h2 class="text-4xl font-black text-gray-900 tracking-tight mb-2 bg-clip-text text-transparent bg-gradient-to-r from-gray-900 via-indigo-900 to-indigo-600">
            {{ __('Welcome Back') }}
        </h2>
        <p class="text-lg text-gray-500 font-medium">
            {{ __('Please sign in to your account') }}
        </p>
    </div>

    <form method="POST" action="{{ route('login') }}" x-data="{ loading: false, showPassword: false }" @submit="loading = true" class="space-y-8">
        @csrf

        <!-- Email Address -->
        <div class="space-y-3">
            <label for="email" class="inline-block px-3 py-1 bg-gray-50 rounded-full text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('Email') }}</label>
            <div class="relative group">
                <input id="email" 
                       class="w-full pl-6 pr-6 py-4 rounded-2xl border-gray-100 bg-gray-50/30 focus:border-pink-500 focus:ring-4 focus:ring-pink-500/10 focus:bg-white transition-all font-semibold text-gray-700 placeholder-gray-400 shadow-inner group-hover:bg-gray-50/50" 
                       type="email" 
                       name="email" 
                       :value="old('email')" 
                       placeholder="Enter your email"
                       required 
                       autofocus 
                       autocomplete="username" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="space-y-3">
            <label for="password" class="inline-block px-3 py-1 bg-gray-50 rounded-full text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('Password') }}</label>

            <div class="relative group">
                <input id="password" 
                       :type="showPassword ? 'text' : 'password'"
                       name="password"
                       class="w-full pl-6 pr-12 py-4 rounded-2xl border-gray-100 bg-gray-50/30 focus:border-pink-500 focus:ring-4 focus:ring-pink-500/10 focus:bg-white transition-all font-semibold text-gray-700 placeholder-gray-400 shadow-inner group-hover:bg-gray-50/50"
                       placeholder="Enter your password"
                       required 
                       autocomplete="current-password" />
                
                <button type="button" 
                        class="absolute inset-y-0 right-0 pr-6 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none transition-colors"
                        @click="showPassword = !showPassword">
                    <svg x-show="!showPassword" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="showPassword" x-cloak class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.024 10.024 0 014.132-5.403m2.134-1.156C10.237 5.163 11.107 5 12 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.403m-5.134 1.156a3 3 0 11-4.243-4.243" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                    </svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
            
            <!-- Rate limiting error display -->
            @if ($errors->has('email') && str_contains($errors->first('email'), 'throttle'))
                <div class="mt-2 text-sm text-red-600 bg-red-50 border border-red-200 rounded-md p-2">
                    <p class="font-medium">{{ __('Too many login attempts') }}</p>
                    <p class="mt-1">{{ $errors->first('email') }}</p>
                    <p class="mt-1 text-xs">{{ __('Please try again later or reset your password.') }}</p>
                </div>
            @endif
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between pt-2">
            <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-pink-600 shadow-sm focus:ring-pink-500 transition-all" name="remember">
                <span class="ms-2 text-[11px] font-bold text-gray-500 uppercase tracking-wider group-hover:text-gray-700 transition-colors">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-[11px] font-bold text-gray-400 hover:text-pink-600 uppercase tracking-wider transition-colors" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <div class="pt-4">
            <button type="submit" 
                    class="w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-xl shadow-purple-200 text-sm font-black uppercase tracking-widest text-white bg-gradient-to-r from-pink-500 to-purple-500 hover:from-pink-600 hover:to-purple-600 focus:outline-none focus:ring-4 focus:ring-offset-2 focus:ring-pink-500/50 transform transition-all duration-300 hover:-translate-y-1 hover:shadow-purple-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                    x-bind:disabled="loading">
                <span x-show="!loading">{{ __('Sign in') }}</span>
                <span x-show="loading" class="inline-flex items-center" x-cloak>
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ __('Signing in...') }}
                </span>
            </button>
        </div>

        <div class="relative py-4">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-100"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-4 bg-white text-[10px] font-black uppercase tracking-widest text-gray-400">
                    {{ __('Or continue with') }}
                </span>
            </div>
        </div>

        <div class="flex justify-center gap-4">
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="text-[11px] font-black text-gray-400 hover:text-pink-600 uppercase tracking-widest transition-colors">
                    {{ __('Create an account') }}
                </a>
            @endif
        </div>
    </form>
</x-guest-layout>
