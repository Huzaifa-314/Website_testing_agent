<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" x-data="{
        password: '',
        passwordStrength: 0,
        passwordStrengthText: '',
        passwordStrengthColor: '',
        showPassword: false,
        showPasswordConfirmation: false,
        checkPasswordStrength() {
            const pwd = this.password;
            let strength = 0;
            
            if (pwd.length >= 8) strength++;
            if (pwd.length >= 12) strength++;
            if (/[a-z]/.test(pwd) && /[A-Z]/.test(pwd)) strength++;
            if (/[0-9]/.test(pwd)) strength++;
            if (/[^a-zA-Z0-9]/.test(pwd)) strength++;
            
            this.passwordStrength = strength;
            
            if (strength <= 1) {
                this.passwordStrengthText = 'Weak';
                this.passwordStrengthColor = 'bg-red-500';
            } else if (strength <= 3) {
                this.passwordStrengthText = 'Medium';
                this.passwordStrengthColor = 'bg-yellow-500';
            } else {
                this.passwordStrengthText = 'Strong';
                this.passwordStrengthColor = 'bg-green-500';
            }
        }
    }">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <p class="mt-1 text-sm text-gray-600">{{ __('You will receive an email verification link after registration.') }}</p>
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <div class="relative">
                <input id="password" 
                       :type="showPassword ? 'text' : 'password'"
                       name="password"
                       x-model="password"
                       @input="checkPasswordStrength()"
                       class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm pr-10"
                       required 
                       autocomplete="new-password" />
                
                <button type="button" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5 focus:outline-none"
                        @click="showPassword = !showPassword">
                    <svg x-show="!showPassword" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="showPassword" x-cloak class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.024 10.024 0 014.132-5.403m2.134-1.156C10.237 5.163 11.107 5 12 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.403m-5.134 1.156a3 3 0 11-4.243-4.243" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                    </svg>
                </button>
            </div>

            <!-- Password Strength Indicator -->
            <div x-show="password.length > 0" class="mt-2">
                <div class="flex items-center gap-2 mb-1">
                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                        <div class="h-2 rounded-full transition-all duration-300" 
                             :class="passwordStrengthColor"
                             :style="'width: ' + (passwordStrength * 20) + '%'"></div>
                    </div>
                    <span class="text-xs font-medium" :class="{
                        'text-red-600': passwordStrength <= 1,
                        'text-yellow-600': passwordStrength > 1 && passwordStrength <= 3,
                        'text-green-600': passwordStrength > 3
                    }" x-text="passwordStrengthText"></span>
                </div>
                <p class="text-xs text-gray-600">
                    {{ __('Use at least 8 characters with a mix of letters, numbers, and symbols.') }}
                </p>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <div class="relative">
                <input id="password_confirmation" 
                       :type="showPasswordConfirmation ? 'text' : 'password'"
                       name="password_confirmation" 
                       class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm pr-10"
                       required 
                       autocomplete="new-password" />
                
                <button type="button" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5 focus:outline-none"
                        @click="showPasswordConfirmation = !showPasswordConfirmation">
                    <svg x-show="!showPasswordConfirmation" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="showPasswordConfirmation" x-cloak class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.024 10.024 0 014.132-5.403m2.134-1.156C10.237 5.163 11.107 5 12 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.403m-5.134 1.156a3 3 0 11-4.243-4.243" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                    </svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>


        <!-- Terms of Service -->
        <div class="mt-4">
            <label for="terms" class="inline-flex items-center">
                <input id="terms" 
                       type="checkbox" 
                       name="terms" 
                       value="1"
                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" 
                       required>
                <span class="ms-2 text-sm text-gray-600">
                    {{ __('I agree to the') }} 
                    <a href="#" class="underline text-indigo-600 hover:text-indigo-900" target="_blank">{{ __('Terms of Service') }}</a>
                    {{ __('and') }}
                    <a href="#" class="underline text-indigo-600 hover:text-indigo-900" target="_blank">{{ __('Privacy Policy') }}</a>
                </span>
            </label>
            <x-input-error :messages="$errors->get('terms')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
