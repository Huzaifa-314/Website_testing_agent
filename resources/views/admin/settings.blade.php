<x-admin-layout>
    <x-slot name="header">
        {{ __('System Settings') }}
    </x-slot>

    <div class="space-y-6">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h3 class="text-lg font-bold mb-6">System Configuration</h3>
                
                <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Site Name -->
                        <div>
                            <label for="site_name" class="block text-sm font-medium text-gray-700 mb-1">
                                Site Name
                            </label>
                            <input type="text" 
                                   name="site_name" 
                                   id="site_name" 
                                   value="{{ old('site_name', $settings['site_name']) }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('site_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Site URL -->
                        <div>
                            <label for="site_url" class="block text-sm font-medium text-gray-700 mb-1">
                                Site URL
                            </label>
                            <input type="url" 
                                   name="site_url" 
                                   id="site_url" 
                                   value="{{ old('site_url', $settings['site_url']) }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('site_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Maintenance Mode -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Maintenance Mode
                            </label>
                            <div class="mt-2">
                                <span class="px-3 py-2 inline-flex text-sm leading-5 font-semibold rounded-full {{ $settings['maintenance_mode'] ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $settings['maintenance_mode'] ? 'Enabled' : 'Disabled' }}
                                </span>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                Use <code class="bg-gray-100 px-1 py-0.5 rounded">php artisan down</code> to enable maintenance mode.
                            </p>
                        </div>

                        <!-- Email Notifications Enabled -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="email_notifications_enabled" 
                                       value="1"
                                       {{ old('email_notifications_enabled', $settings['email_notifications_enabled']) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">Enable Email Notifications</span>
                            </label>
                            @error('email_notifications_enabled')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email From Address -->
                        <div>
                            <label for="email_from_address" class="block text-sm font-medium text-gray-700 mb-1">
                                Email From Address
                            </label>
                            <input type="email" 
                                   name="email_from_address" 
                                   id="email_from_address" 
                                   value="{{ old('email_from_address', $settings['email_from_address']) }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('email_from_address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email From Name -->
                        <div>
                            <label for="email_from_name" class="block text-sm font-medium text-gray-700 mb-1">
                                Email From Name
                            </label>
                            <input type="text" 
                                   name="email_from_name" 
                                   id="email_from_name" 
                                   value="{{ old('email_from_name', $settings['email_from_name']) }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('email_from_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>

