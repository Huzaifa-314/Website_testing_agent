<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Test Definition') }}
            </h2>
            <a href="{{ route('test-definitions.index') }}" class="text-gray-600 hover:text-gray-900">Back to Tests</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6">
                    <form method="POST" action="{{ route('test-definitions.update', $testDefinition) }}">
                        @csrf
                        @method('PUT')
                        
                        <!-- Website Selection -->
                        <div class="mb-6">
                            <x-input-label for="website_id" :value="__('Website')" />
                            <select id="website_id" name="website_id" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                @foreach($websites as $w)
                                    <option value="{{ $w->id }}" {{ ($testDefinition->website_id == $w->id) || old('website_id') == $w->id ? 'selected' : '' }}>
                                        {{ $w->url }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('website_id')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <x-input-label for="description" :value="__('Describe the test in natural language')" />
                            <textarea id="description" name="description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="e.g., Go to login page, enter valid credentials, and verify dashboard loads." required>{{ old('description', $testDefinition->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Scope -->
                        <div class="mb-6">
                            <x-input-label for="test_scope" :value="__('Test Scope')" />
                            <select id="test_scope" name="test_scope" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="form" {{ ($testDefinition->test_scope == 'form') || old('test_scope') == 'form' ? 'selected' : '' }}>Form Submission</option>
                                <option value="auth" {{ ($testDefinition->test_scope == 'auth') || old('test_scope') == 'auth' ? 'selected' : '' }}>Authentication</option>
                                <option value="api" {{ ($testDefinition->test_scope == 'api') || old('test_scope') == 'api' ? 'selected' : '' }}>API Endpoint</option>
                                <option value="workflow" {{ ($testDefinition->test_scope == 'workflow') || old('test_scope') == 'workflow' ? 'selected' : '' }}>End-to-End Workflow</option>
                            </select>
                            <x-input-error :messages="$errors->get('test_scope')" class="mt-2" />
                        </div>

                        <!-- Regenerate Steps Option -->
                        <div class="mb-6">
                            <div class="flex items-center">
                                <input id="regenerate_steps" name="regenerate_steps" type="checkbox" value="1" class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500">
                                <label for="regenerate_steps" class="ml-2 text-sm font-medium text-gray-900">
                                    Regenerate test steps based on updated description
                                </label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Check this if you want to regenerate the test steps with the updated description.</p>
                        </div>

                        <!-- Current Steps Preview -->
                        @if($testDefinition->testCases->isNotEmpty() && $testDefinition->testCases->first()->steps)
                            <div class="mb-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <h3 class="text-sm font-semibold text-gray-700 mb-2">Current Test Steps</h3>
                                <div class="space-y-2">
                                    @foreach($testDefinition->testCases->first()->steps as $index => $step)
                                        <div class="text-sm text-gray-600 font-mono bg-white p-2 rounded">
                                            <span class="font-semibold">{{ $index + 1 }}.</span> {{ $step['action'] ?? 'N/A' }}
                                            @if(isset($step['selector'])) → {{ $step['selector'] }} @endif
                                            @if(isset($step['value'])) → {{ $step['value'] }} @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="flex items-center justify-end gap-4 mt-6">
                            <a href="{{ route('test-definitions.index') }}" class="text-gray-600 hover:text-gray-900 underline">Cancel</a>
                            <x-primary-button type="submit">
                                {{ __('Update Test Definition') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

