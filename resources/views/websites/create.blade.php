<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Website') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('websites.store') }}">
                        @csrf

                        <!-- URL Address -->
                        <div>
                            <x-input-label for="url" :value="__('Website URL')" />
                            <x-text-input id="url" class="block mt-1 w-full" type="url" name="url" :value="old('url')" required autofocus placeholder="https://example.com" />
                            <x-input-error :messages="$errors->get('url')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-4">
                                {{ __('Add Website') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
