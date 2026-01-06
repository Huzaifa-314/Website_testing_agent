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
                                     data-description="{{ $template->example_description ?? $template->description }}"
                                     data-scope="{{ $template->test_scope }}">
                                    <div class="flex items-start justify-between mb-2">
                                        <h4 class="font-semibold text-gray-900">{{ $template->name }}</h4>
                                        @if($template->is_system)
                                            <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded">System</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600 mb-2">{{ $template->description }}</p>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded">{{ ucfirst($template->test_scope) }}</span>
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
                        </div>

                        <!-- Scope -->
                        <div class="mb-6">
                            <x-input-label for="test_scope" :value="__('Test Scope')" />
                            <select id="test_scope" name="test_scope" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="">-- Select test scope --</option>
                                <option value="form" {{ old('test_scope') == 'form' ? 'selected' : '' }}>Form Submission</option>
                                <option value="auth" {{ old('test_scope') == 'auth' ? 'selected' : '' }}>Authentication</option>
                                <option value="api" {{ old('test_scope') == 'api' ? 'selected' : '' }}>API Endpoint</option>
                                <option value="workflow" {{ old('test_scope') == 'workflow' ? 'selected' : '' }}>End-to-End Workflow</option>
                            </select>
                            <x-input-error :messages="$errors->get('test_scope')" class="mt-2" />
                        </div>

                        <!-- Preview Section -->
                        <div id="preview-section" class="mb-6 hidden">
                            <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                                <h3 class="text-lg font-semibold text-indigo-900 mb-3">Generated Test Steps Preview</h3>
                                <div id="preview-content" class="space-y-2">
                                    <!-- Preview will be generated here -->
                                </div>
                                <button type="button" id="regenerate-preview" class="mt-3 text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                    Regenerate Preview
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
                            <button type="button" id="preview-btn" class="px-4 py-2 bg-gray-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                                Preview Steps
                            </button>
                            <x-primary-button type="submit">
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
            const scopeField = document.getElementById('test_scope');

            // Template selection
            document.querySelectorAll('.template-card').forEach(card => {
                card.addEventListener('click', function() {
                    const description = this.dataset.description;
                    const scope = this.dataset.scope;
                    
                    descriptionField.value = description;
                    scopeField.value = scope;
                    
                    // Highlight selected template
                    document.querySelectorAll('.template-card').forEach(c => c.classList.remove('ring-2', 'ring-indigo-500'));
                    this.classList.add('ring-2', 'ring-indigo-500');
                    
                    // Auto-generate preview
                    setTimeout(generatePreview, 300);
                });
            });

            function generatePreview() {
                const description = descriptionField.value.trim();
                const scope = scopeField.value;

                if (!description || !scope) {
                    alert('Please fill in both description and test scope to generate preview.');
                    return;
                }

                // Show loading state
                previewContent.innerHTML = '<div class="text-indigo-600">Generating preview...</div>';
                previewSection.classList.remove('hidden');

                // Simulate AI generation (in real app, this would be an AJAX call)
                setTimeout(() => {
                    const mockSteps = generateMockSteps(description, scope);
                    displayPreview(mockSteps);
                }, 500);
            }

            function generateMockSteps(description, scope) {
                const steps = [];
                const desc = description.toLowerCase();

                if (scope === 'auth' || desc.includes('login')) {
                    steps.push({ action: 'visit', url: '/login', description: 'Navigate to login page' });
                    steps.push({ action: 'type', selector: 'input[name="email"]', value: 'test@example.com', description: 'Enter email address' });
                    steps.push({ action: 'type', selector: 'input[name="password"]', value: 'password', description: 'Enter password' });
                    steps.push({ action: 'click', selector: 'button[type="submit"]', description: 'Click login button' });
                    steps.push({ action: 'assert_url', value: '/dashboard', description: 'Verify redirect to dashboard' });
                } else if (scope === 'form' || desc.includes('form')) {
                    steps.push({ action: 'visit', url: '/contact', description: 'Navigate to contact form' });
                    steps.push({ action: 'type', selector: 'input[name="name"]', value: 'John Doe', description: 'Enter name' });
                    steps.push({ action: 'type', selector: 'textarea[name="message"]', value: 'Hello World', description: 'Enter message' });
                    steps.push({ action: 'click', selector: 'button[type="submit"]', description: 'Submit form' });
                    steps.push({ action: 'assert_text', value: 'Thank you', description: 'Verify success message' });
                } else {
                    steps.push({ action: 'visit', url: '/', description: 'Navigate to homepage' });
                    steps.push({ action: 'assert_status', value: 200, description: 'Verify page loads successfully' });
                }

                return steps;
            }

            function displayPreview(steps) {
                let html = '<div class="space-y-3">';
                steps.forEach((step, index) => {
                    html += `<div class="bg-white p-3 rounded border border-indigo-100">
                        <div class="flex items-start gap-3">
                            <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-700 rounded-full flex items-center justify-center text-xs font-bold">${index + 1}</span>
                            <div class="flex-grow">
                                <div class="font-semibold text-gray-900">${step.description || step.action}</div>
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
        });
    </script>
</x-app-layout>
