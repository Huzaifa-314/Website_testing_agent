<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create New Test Definition') }}
            </h2>
            <a href="{{ route('test-definitions.index') }}" class="text-gray-600 hover:text-gray-900">Back to Tests</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Templates Section -->
            @if($templates->isNotEmpty())
                <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Test Definition Templates</h3>
                        <p class="text-sm text-gray-500 mb-4">Start with a template or create from scratch</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($templates as $template)
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-indigo-300 hover:shadow-md transition cursor-pointer template-card" 
                                     data-template-id="{{ $template->id }}"
                                     data-description="{{ $template->example_description ?? $template->description }}">
                                    <div class="flex items-start justify-between mb-2">
                                        <h4 class="font-semibold text-gray-900">{{ $template->name }}</h4>
                                        @if($template->is_system)
                                            <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded">System</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600 mb-2">{{ $template->description }}</p>
                                    <div class="flex items-center gap-2">
                                        <button type="button" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium use-template-btn">
                                            Use Template
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6">
                    <form id="test-form" method="POST" action="{{ route('test-definitions.store') }}">
                        @csrf
                        
                        <!-- Website Selection -->
                        <div class="mb-6">
                            <x-input-label for="website_id" :value="__('Select Website')" />
                            <select id="website_id" name="website_id" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="">-- Select a website --</option>
                                @foreach($websites as $w)
                                    <option value="{{ $w->id }}" {{ ($website && $website->id == $w->id) || old('website_id') == $w->id ? 'selected' : '' }}>
                                        {{ $w->url }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('website_id')" class="mt-2" />
                            @if($websites->isEmpty())
                                <p class="mt-2 text-sm text-gray-500">
                                    <a href="{{ route('websites.create') }}" class="text-indigo-600 hover:underline">Add a website first</a>
                                </p>
                            @endif
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <x-input-label for="description" :value="__('Describe the test in natural language')" />
                            <textarea id="description" name="description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="e.g., Go to login page, enter valid credentials, and verify dashboard loads." required>{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            <p class="mt-1 text-sm text-gray-500">Describe what you want to test in plain English.</p>
                            
                            <!-- Generate Steps Button -->
                            <div class="mt-4">
                                <button type="button" id="preview-btn" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                    Generate Steps
                                </button>
                            </div>
                        </div>

                        <!-- Hidden fields to store generated steps and metadata -->
                        <input type="hidden" id="generated-steps" name="generated_steps" value="">
                        <input type="hidden" id="generated-metadata" name="generated_metadata" value="">

                        <!-- Preview Section -->
                        <div id="preview-section" class="mb-6 hidden">
                            <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                                <h3 class="text-lg font-semibold text-indigo-900 mb-3">Generated Test Steps Preview</h3>
                                <div id="preview-content" class="space-y-2">
                                    <!-- Preview will be generated here -->
                                </div>
                                <button type="button" id="regenerate-preview" class="mt-3 text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                    Regenerate Steps
                                </button>
                            </div>
                        </div>

                        <!-- Options -->
                        <div class="mb-6">
                            <div class="flex items-center">
                                <input id="execute_immediately" name="execute_immediately" type="checkbox" value="1" class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500">
                                <label for="execute_immediately" class="ml-2 text-sm font-medium text-gray-900">
                                    Execute test immediately after saving
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4 mt-6">
                            <a href="{{ route('test-definitions.index') }}" class="text-gray-600 hover:text-gray-900 underline">Cancel</a>
                            <x-primary-button type="submit" id="save-btn" disabled>
                                {{ __('Save Test Definition') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const previewBtn = document.getElementById('preview-btn');
            const previewSection = document.getElementById('preview-section');
            const previewContent = document.getElementById('preview-content');
            const regenerateBtn = document.getElementById('regenerate-preview');
            const form = document.getElementById('test-form');
            const descriptionField = document.getElementById('description');
            const saveBtn = document.getElementById('save-btn');
            const generatedStepsField = document.getElementById('generated-steps');
            const generatedMetadataField = document.getElementById('generated-metadata');

            // Template selection
            document.querySelectorAll('.template-card').forEach(card => {
                card.addEventListener('click', function() {
                    const description = this.dataset.description;
                    
                    descriptionField.value = description;
                    
                    // Highlight selected template
                    document.querySelectorAll('.template-card').forEach(c => c.classList.remove('ring-2', 'ring-indigo-500'));
                    this.classList.add('ring-2', 'ring-indigo-500');
                    
                    // Auto-generate preview
                    setTimeout(generatePreview, 300);
                });
            });

            function generatePreview() {
                const description = descriptionField.value.trim();
                const websiteId = document.getElementById('website_id').value;

                if (!description) {
                    alert('Please fill in the description to generate preview.');
                    return;
                }

                // Show loading state with Gemini feedback
                previewContent.innerHTML = '<div class="flex items-center gap-3 text-indigo-600"><svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Fetching from Gemini AI...</span></div>';
                previewSection.classList.remove('hidden');
                
                // Disable preview button during generation
                previewBtn.disabled = true;
                previewBtn.textContent = 'Generating...';

                // Make AJAX call to preview endpoint
                fetch('{{ route("test-definitions.preview") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        description: description,
                        website_id: websiteId || null
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Store generated steps and metadata in hidden fields
                        generatedStepsField.value = JSON.stringify(data.steps);
                        generatedMetadataField.value = JSON.stringify(data.metadata || {});
                        displayPreview(data.steps, data.metadata);
                        // Enable save button
                        saveBtn.disabled = false;
                    } else {
                        previewContent.innerHTML = `<div class="text-red-600 bg-red-50 border border-red-200 rounded p-3">Error: ${data.error || 'Failed to generate preview'}</div>`;
                        // Clear generated steps and disable save button
                        generatedStepsField.value = '';
                        generatedMetadataField.value = '';
                        saveBtn.disabled = true;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    previewContent.innerHTML = `<div class="text-red-600 bg-red-50 border border-red-200 rounded p-3">Error: Failed to connect to server. Please try again.</div>`;
                    // Clear generated steps and disable save button
                    generatedStepsField.value = '';
                    generatedMetadataField.value = '';
                    saveBtn.disabled = true;
                })
                .finally(() => {
                    // Re-enable preview button
                    previewBtn.disabled = false;
                    previewBtn.textContent = 'Generate Steps';
                });
            }

            function displayPreview(steps, metadata = null) {
                let html = '<div class="space-y-3">';
                
                steps.forEach((step, index) => {
                    // Generate a description if not provided
                    let stepDescription = step.description;
                    if (!stepDescription) {
                        if (step.action === 'visit') {
                            stepDescription = `Navigate to ${step.url}`;
                        } else if (step.action === 'type') {
                            stepDescription = `Type "${step.value}" into ${step.selector}`;
                        } else if (step.action === 'click') {
                            stepDescription = `Click on ${step.selector}`;
                        } else if (step.action === 'assert_url') {
                            stepDescription = `Verify URL matches ${step.value}`;
                        } else if (step.action === 'assert_text') {
                            stepDescription = `Verify text "${step.value}" appears on page`;
                        } else if (step.action === 'assert_status') {
                            stepDescription = `Verify HTTP status is ${step.value}`;
                        } else {
                            stepDescription = `${step.action} action`;
                        }
                    }
                    
                    html += `<div class="bg-white p-3 rounded border border-indigo-100">
                        <div class="flex items-start gap-3">
                            <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-700 rounded-full flex items-center justify-center text-xs font-bold">${index + 1}</span>
                            <div class="flex-grow">
                                <div class="font-semibold text-gray-900">${stepDescription}</div>
                                <div class="text-sm text-gray-600 font-mono mt-1">
                                    ${formatStep(step)}
                                </div>
                            </div>
                        </div>
                    </div>`;
                });
                html += '</div>';
                previewContent.innerHTML = html;
            }

            function formatStep(step) {
                let parts = [`action: ${step.action}`];
                if (step.url) parts.push(`url: ${step.url}`);
                if (step.selector) parts.push(`selector: ${step.selector}`);
                if (step.value) parts.push(`value: ${step.value}`);
                return parts.join(' | ');
            }

            previewBtn.addEventListener('click', generatePreview);
            regenerateBtn.addEventListener('click', generatePreview);

            // Prevent form submission if steps haven't been generated
            form.addEventListener('submit', function(e) {
                if (!generatedStepsField.value || generatedStepsField.value.trim() === '') {
                    e.preventDefault();
                    alert('Please generate test steps before saving the test definition.');
                    return false;
                }
            });
        });
    </script>
</x-app-layout>
