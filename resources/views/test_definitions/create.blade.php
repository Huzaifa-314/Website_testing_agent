<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Define New Test for') }} {{ $website->url }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('test-definitions.store') }}">
                        @csrf
                        <input type="hidden" name="website_id" value="{{ $website->id }}">

                        <!-- Description -->
                        <div class="mb-4">
                            <x-input-label for="description" :value="__('Describe the test in natural language')" />
                            <textarea id="description" name="description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="e.g., Go to login page, enter valid credentials, and verify dashboard loads." required>{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Scope -->
                        <div class="mb-4">
                            <x-input-label for="test_scope" :value="__('Test Scope')" />
                            <select id="test_scope" name="test_scope" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="form" {{ old('test_scope') == 'form' ? 'selected' : '' }}>Form Submission</option>
                                <option value="auth" {{ old('test_scope') == 'auth' ? 'selected' : '' }}>Authentication</option>
                                <option value="api" {{ old('test_scope') == 'api' ? 'selected' : '' }}>API Endpoint</option>
                                <option value="workflow" {{ old('test_scope') == 'workflow' ? 'selected' : '' }}>End-to-End Workflow</option>
                            </select>
                            <x-input-error :messages="$errors->get('test_scope')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('websites.show', $website) }}" class="text-gray-600 underline mr-4">Cancel</a>
                            <x-primary-button>
                                {{ __('Save Test Definition') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
