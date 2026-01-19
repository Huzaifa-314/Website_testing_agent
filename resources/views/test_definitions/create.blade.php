<x-dashboard-layout>
    <div class="mb-10">
        <div class="flex items-center gap-2 mb-4">
            <a href="{{ route('test-definitions.index') }}" class="text-[10px] font-black uppercase tracking-widest text-indigo-500 hover:text-indigo-700 transition-colors">Test Definitions</a>
            <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Create New</span>
        </div>
        <h1 class="text-4xl font-black text-gray-900 tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-indigo-600">
            Design New Test
        </h1>
        <p class="text-lg text-gray-500 font-medium mt-2">Use natural language to define complex user flows.</p>
    </div>

    @if(session('error'))
        <div class="mb-8 bg-red-50 border border-red-100 text-red-700 px-6 py-4 rounded-2xl flex items-center gap-3 animate-in fade-in slide-in-from-top-4">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-bold">{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Main Form Section -->
        <div class="lg:col-span-8 space-y-8">
            <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm p-8 lg:p-10 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-8 opacity-5">
                    <svg class="w-32 h-32 text-indigo-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L4.5 20.29l.71.71L12 18l6.79 3 .71-.71z"/></svg>
                </div>
                
                <form id="test-form" method="POST" action="{{ route('test-definitions.store') }}" class="space-y-8 relative z-10">
                    @csrf
                    
                    <!-- Website Selection -->
                    <div class="space-y-4">
                        <label for="website_id" class="inline-block px-3 py-1 bg-indigo-50 rounded-full text-[10px] font-black text-indigo-600 uppercase tracking-[0.2em]">1. Select Target Website</label>
                        <div class="relative group">
                            <select id="website_id" name="website_id" required class="w-full h-[60px] pl-6 pr-12 rounded-2xl border-gray-100 bg-gray-50/50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 focus:bg-white transition-all font-semibold text-gray-700 appearance-none bg-none shadow-inner group-hover:bg-gray-50/80">
                                <option value="">Where should we test?</option>
                                @foreach($websites as $w)
                                    <option value="{{ $w->id }}" {{ ($website && $website->id == $w->id) || old('website_id') == $w->id ? 'selected' : '' }}>
                                        {{ $w->url }}
                                    </option>
                                @endforeach
                            </select>
                            <svg class="w-5 h-5 text-gray-400 absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                        <x-input-error :messages="$errors->get('website_id')" class="mt-2" />
                    </div>

                    <!-- Description -->
                    <div class="space-y-4">
                        <label for="description" class="inline-block px-3 py-1 bg-indigo-50 rounded-full text-[10px] font-black text-indigo-600 uppercase tracking-[0.2em]">2. Describe User Flow</label>
                        <textarea 
                            id="description" 
                            name="description" 
                            rows="4" 
                            class="w-full p-6 rounded-3xl border-gray-100 bg-gray-50/50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 focus:bg-white transition-all font-semibold text-gray-700 placeholder-gray-400 shadow-inner group-hover:bg-gray-50/80 leading-relaxed" 
                            placeholder="e.g., Navigate to login page, enter my email and password, then verify that the welcome message appears on the dashboard." 
                            required>{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Natural Language Processing Powered by Gemini</p>
                            <button type="button" id="preview-btn" class="group px-8 py-3 bg-gradient-to-r from-pink-500 to-purple-500 text-white font-black text-xs uppercase tracking-[0.2em] rounded-xl hover:shadow-purple-200 transition-all shadow-xl shadow-purple-100 active:scale-95 flex items-center gap-2">
                                <svg class="w-4 h-4 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                <span>Generate Steps</span>
                            </button>
                        </div>
                    </div>

                    <!-- Hidden fields to store generated steps and metadata -->
                    <input type="hidden" id="generated-steps" name="generated_steps" value="">
                    <input type="hidden" id="generated-metadata" name="generated_metadata" value="">

                    <!-- Preview Section -->
                    <div id="preview-section" class="hidden animate-in fade-in slide-in-from-bottom-4 duration-500">
                        <div class="bg-indigo-50/50 border border-indigo-100 rounded-[2rem] p-8 lg:p-10 relative">
                            <div class="flex items-center justify-between mb-8">
                                <h3 class="text-lg font-black text-indigo-900 leading-none">AI Generated Execution Plan</h3>
                                <button type="button" id="regenerate-preview" class="text-[10px] font-black text-indigo-600 uppercase tracking-widest hover:text-indigo-800 transition-colors">Regenerate</button>
                            </div>
                            <div id="preview-content" class="space-y-4">
                                <!-- Preview will be generated here -->
                            </div>
                        </div>
                    </div>

                    <!-- Options -->
                    <div class="bg-gray-50/50 rounded-2xl p-6 border border-gray-100/50">
                        <label class="flex items-center cursor-pointer group">
                            <div class="relative">
                                <input id="execute_immediately" name="execute_immediately" type="checkbox" value="1" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-emerald-500 transition-colors"></div>
                                <div class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform peer-checked:translate-x-5"></div>
                            </div>
                            <span class="ml-4 text-sm font-black text-gray-700 uppercase tracking-widest group-hover:text-gray-900 transition-colors">
                                Execute test immediately after saving
                            </span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end gap-6 pt-6 border-t border-gray-50">
                        <a href="{{ route('test-definitions.index') }}" class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-gray-900 transition-colors">Discard</a>
                        <button type="submit" id="save-btn" disabled class="px-10 py-4 bg-gradient-to-r from-pink-500 to-purple-500 text-white font-black text-xs uppercase tracking-[0.2em] rounded-2xl hover:scale-105 transition-all shadow-xl shadow-purple-200 hover:shadow-purple-300 disabled:opacity-30 disabled:cursor-not-allowed">
                            Save Definition
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar Templates -->
        <div class="lg:col-span-4 space-y-8">
            <div class="bg-white rounded-[2.5rem] border border-gray-100 p-8 shadow-sm">
                <div class="flex items-center gap-3 mb-8">
                    <div class="bg-indigo-100 p-2 rounded-xl text-indigo-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest leading-none">Smart Templates</h3>
                </div>

                @if($templates->isNotEmpty())
                    <div class="space-y-4">
                        @foreach($templates as $template)
                            <div class="group border border-gray-100 rounded-[2rem] p-6 hover:border-indigo-200 hover:shadow-xl hover:shadow-indigo-50 transition-all duration-300 cursor-pointer template-card bg-gray-50/30 hover:bg-white" 
                                 data-template-id="{{ $template->id }}"
                                 data-description="{{ $template->example_description ?? $template->description }}">
                                <div class="flex items-start justify-between mb-4">
                                    <h4 class="font-black text-gray-900 group-hover:text-indigo-600 transition-colors uppercase tracking-wide text-xs">{{ $template->name }}</h4>
                                    @if($template->is_system)
                                        <span class="text-[8px] px-2 py-1 bg-indigo-100 text-indigo-700 font-black uppercase tracking-widest rounded-full">System</span>
                                    @endif
                                </div>
                                <p class="text-[11px] font-medium text-gray-500 group-hover:text-gray-600 transition-colors leading-relaxed mb-6">{{ $template->description }}</p>
                                <button type="button" class="text-[10px] font-black text-indigo-600 uppercase tracking-widest group-hover:translate-x-1 transition-transform flex items-center gap-2">
                                    <span>Use Now</span>
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-10 px-6">
                        <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-dashed border-gray-200">
                            <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        </div>
                        <p class="text-[10px] font-black text-gray-300 uppercase tracking-[0.2em]">No templates available</p>
                    </div>
                @endif
            </div>

            <div class="bg-gray-900 rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-2xl shadow-indigo-100">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-indigo-500/20 rounded-full blur-3xl"></div>
                <h3 class="text-xs font-black text-indigo-400 uppercase tracking-widest mb-4 relative z-10">Pro Tip</h3>
                <p class="text-sm font-medium text-gray-300 leading-relaxed relative z-10">Be as specific as possible with URLs and button text to help the AI generate highly accurate test steps.</p>
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
                    document.querySelectorAll('.template-card').forEach(c => c.classList.remove('border-indigo-500', 'ring-4', 'ring-indigo-500/10'));
                    this.classList.add('border-indigo-500', 'ring-4', 'ring-indigo-500/10');
                    
                    // Scroll to form
                    descriptionField.focus();
                    
                    // Auto-generate preview
                    setTimeout(generatePreview, 300);
                });
            });

            function generatePreview() {
                const description = descriptionField.value.trim();
                const websiteId = document.getElementById('website_id').value;

                if (!description) {
                    alert('Please describe your user flow first.');
                    return;
                }

                // Show loading state
                previewContent.innerHTML = `
                    <div class="flex flex-col items-center justify-center p-12 text-indigo-600 bg-white rounded-3xl border border-indigo-100 shadow-inner">
                        <div class="relative w-16 h-16 mb-6">
                            <div class="absolute inset-0 border-4 border-indigo-100 rounded-full"></div>
                            <div class="absolute inset-0 border-4 border-indigo-600 rounded-full border-t-transparent animate-spin"></div>
                        </div>
                        <span class="text-sm font-black uppercase tracking-[0.2em] animate-pulse">Gemini AI is designing your test...</span>
                    </div>`;
                previewSection.classList.remove('hidden');
                
                // Disable preview button during generation
                previewBtn.disabled = true;
                const originalBtnText = previewBtn.innerHTML;
                previewBtn.innerHTML = '<span>Processing...</span>';

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
                        generatedStepsField.value = JSON.stringify(data.steps);
                        generatedMetadataField.value = JSON.stringify(data.metadata || {});
                        displayPreview(data.steps, data.metadata);
                        saveBtn.disabled = false;
                        
                        // Scroll to preview
                        previewSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    } else {
                        previewContent.innerHTML = `<div class="text-red-600 font-bold p-8 bg-white rounded-3xl border border-red-100 shadow-inner text-center">AI Generation Error: ${data.error || 'Failed to design steps'}</div>`;
                        generatedStepsField.value = '';
                        generatedMetadataField.value = '';
                        saveBtn.disabled = true;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    previewContent.innerHTML = `<div class="text-red-600 font-bold p-8 bg-white rounded-3xl border border-red-100 shadow-inner text-center">Connection Error: Failed to reach the testing agent.</div>`;
                    generatedStepsField.value = '';
                    generatedMetadataField.value = '';
                    saveBtn.disabled = true;
                })
                .finally(() => {
                    previewBtn.disabled = false;
                    previewBtn.innerHTML = originalBtnText;
                });
            }

            function displayPreview(steps, metadata = null) {
                let html = '<div class="space-y-4">';
                
                steps.forEach((step, index) => {
                    let stepDescription = step.description;
                    if (!stepDescription) {
                        if (step.action === 'visit') stepDescription = `Target: ${step.url}`;
                        else if (step.action === 'type') stepDescription = `Input: "${step.value}" in ${step.selector}`;
                        else if (step.action === 'click') stepDescription = `Interact: Click ${step.selector}`;
                        else if (step.action === 'assert_url') stepDescription = `Verify URL path`;
                        else if (step.action === 'assert_text') stepDescription = `Verify text presence`;
                        else stepDescription = `${step.action} automated action`;
                    }
                    
                    html += `
                        <div class="bg-white p-5 rounded-2xl border border-indigo-100 shadow-sm flex items-start gap-4 hover:shadow-md transition-shadow">
                            <div class="flex-shrink-0 w-8 h-8 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center text-xs font-black">${index + 1}</div>
                            <div class="flex-grow">
                                <div class="text-xs font-black text-gray-900 uppercase tracking-wide mb-1">${stepDescription}</div>
                                <div class="text-[9px] font-bold text-gray-400 font-mono tracking-wider break-all bg-gray-50/50 p-2 rounded-lg">
                                    ${formatStep(step)}
                                </div>
                            </div>
                        </div>`;
                });
                html += '</div>';
                previewContent.innerHTML = html;
            }

            function formatStep(step) {
                let parts = [];
                if (step.action) parts.push(`<span class="text-indigo-500">${step.action}</span>`);
                if (step.url) parts.push(`path: ${step.url}`);
                if (step.selector) parts.push(`el: ${step.selector}`);
                if (step.value) parts.push(`val: ${step.value}`);
                return parts.join(' <span class="text-gray-200 mx-1">|</span> ');
            }

            previewBtn.addEventListener('click', generatePreview);
            regenerateBtn.addEventListener('click', generatePreview);

            form.addEventListener('submit', function(e) {
                if (!generatedStepsField.value || generatedStepsField.value.trim() === '') {
                    e.preventDefault();
                    alert('Please generate and review test steps before saving.');
                    return false;
                }
            });
        });
    </script>
</x-dashboard-layout>
