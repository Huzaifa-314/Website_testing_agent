<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <nav class="flex text-sm text-gray-500 mb-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('websites.index') }}" class="inline-flex items-center hover:text-indigo-600 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                My Websites
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <a href="{{ route('websites.show', $website) }}" class="ml-1 text-gray-500 hover:text-indigo-600 transition-colors duration-200">
                                    {{ Str::limit($website->url, 30) }}
                                </a>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <span class="ml-1 text-gray-700 font-medium">Edit</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-600">
                    {{ __('Edit Website Settings') }}
                </h2>
            </div>
            
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Main Settings Card -->
            <div class="bg-white/80 backdrop-blur-xl border border-white/20 shadow-xl sm:rounded-2xl overflow-hidden md:p-8 p-6 ring-1 ring-black/5 relative">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-32 h-32 bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative">
                    <header class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">General Information</h3>
                        <p class="mt-1 text-sm text-gray-600">Update your website details and monitoring status.</p>
                    </header>

                    <form method="POST" action="{{ route('websites.update', $website) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- URL Address -->
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100/50 hover:border-indigo-100 transition-colors duration-300">
                            <x-input-label for="url" :value="__('Website URL')" class="text-gray-700 font-semibold mb-1" />
                            <div class="relative mt-1">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                </div>
                                <x-text-input id="url" class="block w-full pl-10 border-gray-200 focus:border-indigo-500 focus:ring-indigo-500/20 rounded-lg shadow-sm transition-all duration-200" type="url" name="url" :value="old('url', $website->url)" required autofocus placeholder="https://example.com" />
                            </div>
                            <x-input-error :messages="$errors->get('url')" class="mt-2" />
                            <p class="mt-2 text-xs text-gray-500">Enter the full URL including http:// or https://</p>
                        </div>

                        <!-- Status -->
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100/50 hover:border-indigo-100 transition-colors duration-300">
                            <x-input-label for="status" :value="__('Monitoring Status')" class="text-gray-700 font-semibold mb-1" />
                            <div class="mt-1 relative">
                                <select id="status" name="status" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-lg shadow-sm">
                                    <option value="active" {{ old('status', $website->status) === 'active' ? 'selected' : '' }}>Active - Monitoring Enabled</option>
                                    <option value="inactive" {{ old('status', $website->status) === 'inactive' ? 'selected' : '' }}>Inactive - Monitoring Paused</option>
                                    <option value="pending" {{ old('status', $website->status) === 'pending' ? 'selected' : '' }}>Pending - Setup Required</option>
                                    <option value="error" {{ old('status', $website->status) === 'error' ? 'selected' : '' }}>Error - Issue Detected</option>
                                </select>
                            </div>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100">
                            <a href="{{ route('websites.show', $website) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:from-indigo-700 hover:to-purple-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50">
                                {{ __('Update Website') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="bg-red-50/50 backdrop-blur-xl border border-red-100 shadow-sm sm:rounded-2xl overflow-hidden md:p-8 p-6 ring-1 ring-red-100">
                <header class="flex items-start gap-4 mb-6">
                    <div class="p-2 bg-red-100 rounded-lg text-red-600">
                         <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-red-900">Danger Zone</h3>
                        <p class="mt-1 text-sm text-red-700">Once you delete a website, there is no going back. All associated data will be permanently removed.</p>
                    </div>
                </header>

                <div class="flex items-center justify-end">
                    <form method="POST" action="{{ route('websites.destroy', $website) }}" onsubmit="return confirm('Are you sure you want to delete this website? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-lg text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200">
                            Delete Website
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>


