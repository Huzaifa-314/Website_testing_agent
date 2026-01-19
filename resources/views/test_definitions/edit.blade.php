<x-dashboard-layout>
    <div class="mb-10">
        <div class="flex items-center gap-2 mb-4">
            <a href="{{ route('test-definitions.index') }}" class="text-[10px] font-black uppercase tracking-widest text-indigo-500 hover:text-indigo-700 transition-colors">Test Definitions</a>
            <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Edit Test</span>
        </div>
        <h1 class="text-4xl font-black text-gray-900 tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-indigo-600">
            Edit Test Profile
        </h1>
        <p class="text-lg text-gray-500 font-medium mt-2">Refine your automated testing parameters.</p>
    </div>

    <div class="max-w-4xl">
        <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm p-8 lg:p-10 relative overflow-hidden">
            <form id="test-form" method="POST" action="{{ route('test-definitions.update', $testDefinition) }}" class="space-y-8 relative z-10">
                @csrf
                @method('PUT')
                
                <!-- Website Selection -->
                <div class="space-y-4">
                    <label for="website_id" class="inline-block px-3 py-1 bg-indigo-50 rounded-full text-[10px] font-black text-indigo-600 uppercase tracking-[0.2em]">Target Website</label>
                    <div class="relative group">
                        <select id="website_id" name="website_id" required class="w-full h-[60px] pl-6 pr-12 rounded-2xl border-gray-100 bg-gray-50/50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 focus:bg-white transition-all font-semibold text-gray-700 appearance-none bg-none shadow-inner group-hover:bg-gray-50/80">
                            @foreach($websites as $w)
                                <option value="{{ $w->id }}" {{ ($testDefinition->website_id == $w->id) || old('website_id') == $w->id ? 'selected' : '' }}>
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
                    <label for="description" class="inline-block px-3 py-1 bg-indigo-50 rounded-full text-[10px] font-black text-indigo-600 uppercase tracking-[0.2em]">Describe User Flow</label>
                    <textarea 
                        id="description" 
                        name="description" 
                        rows="4" 
                        class="w-full p-6 rounded-3xl border-gray-100 bg-gray-50/50 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 focus:bg-white transition-all font-semibold text-gray-700 placeholder-gray-400 shadow-inner group-hover:bg-gray-50/80 leading-relaxed" 
                        placeholder="e.g., Navigate to login page, enter my email and password..." 
                        required>{{ old('description', $testDefinition->description) }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    
                    <div class="flex items-center justify-between pt-4">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest leading-none">Powered by Gemini AI Engine</p>
                        <button type="button" id="preview-btn" class="group px-8 py-3 bg-gradient-to-r from-pink-500 to-purple-500 text-white font-black text-xs uppercase tracking-[0.2em] rounded-xl hover:shadow-purple-200 transition-all shadow-xl shadow-purple-100 active:scale-95 flex items-center gap-2">
                            <svg class="w-4 h-4 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            <span>Regenerate Steps</span>
                        </button>
                    </div>
                </div>

                <!-- Hidden fields for new steps -->
                <input type="hidden" id="generated-steps" name="generated_steps" value="">
                <input type="hidden" id="generated-metadata" name="generated_metadata" value="">

                <!-- New Preview Section -->
                <div id="preview-section" class="hidden animate-in fade-in slide-in-from-bottom-4 duration-500">
                    <div class="bg-indigo-50/50 border border-indigo-100 rounded-[2rem] p-8 lg:p-10">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-sm font-black text-indigo-900 uppercase tracking-widest leading-none">New Execution Plan</h3>
                            <button type="button" id="regenerate-preview" class="text-[10px] font-black text-indigo-600 uppercase tracking-widest">Retry AI Generation</button>
                        </div>
                        <div id="preview-content" class="space-y-4"></div>
                    </div>
                </div>

                <!-- Current Steps Preview (if no new steps generated) -->
                @if($testDefinition->testCases->isNotEmpty() && $testDefinition->testCases->first()->steps)
                    <div id="current-steps-section" class="bg-gray-50/50 border border-gray-100 rounded-[2rem] p-8">
                        <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6">Current Execution Steps</h3>
                        <div class="space-y-3">
                            @foreach($testDefinition->testCases->first()->steps as $index => $step)
                                <div class="bg-white p-4 rounded-xl border border-gray-100/50 flex items-center gap-4">
                                    <span class="flex-shrink-0 w-6 h-6 bg-gray-50 text-gray-400 rounded flex items-center justify-center text-[10px] font-black">{{ $index + 1 }}</span>
                                    <div class="text-[11px] font-bold text-gray-600 font-mono flex-grow">
                                        <span class="text-indigo-500 uppercase">{{ $step['action'] ?? 'N/A' }}</span> 
                                        @if(isset($step['selector'])) <span class="text-gray-300 px-1">|</span> {{ $step['selector'] }} @endif
                                        @if(isset($step['value'])) <span class="text-gray-300 px-1">|</span> {{ $step['value'] }} @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="flex items-center justify-end gap-6 pt-6 border-t border-gray-50">
                    <a href="{{ route('test-definitions.index') }}" class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-gray-900">Cancel</a>
                    <button type="submit" id="save-btn" class="px-10 py-4 bg-gradient-to-r from-pink-500 to-purple-500 text-white font-black text-xs uppercase tracking-[0.2em] rounded-2xl hover:scale-105 transition-all shadow-xl shadow-purple-200 hover:shadow-purple-300">
                        Update Definition
                    </button>
                </div>
            </form>
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
            const currentStepsSection = document.getElementById('current-steps-section');

            function generatePreview() {
                const description = descriptionField.value.trim();
                const websiteId = document.getElementById('website_id').value;

                if (!description) {
                    alert('Please describe your user flow first.');
                    return;
                }

                previewContent.innerHTML = `
                    <div class="flex flex-col items-center justify-center p-8 text-indigo-600 bg-white rounded-2xl border border-indigo-100 shadow-inner">
                        <div class="w-10 h-10 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin mb-4"></div>
                        <span class="text-[10px] font-black uppercase tracking-widest animate-pulse">Redesigning steps...</span>
                    </div>`;
                previewSection.classList.remove('hidden');
                
                if (currentStepsSection) {
                    currentStepsSection.classList.add('hidden');
                }
                
                previewBtn.disabled = true;
                previewBtn.innerHTML = '<span>Processing...</span>';

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
                        previewSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    } else {
                        previewContent.innerHTML = `<div class="text-red-600 font-bold p-6 bg-white rounded-2xl border border-red-100 text-center text-xs">AI Error: ${data.error || 'Failed to design steps'}</div>`;
                        generatedStepsField.value = '';
                        generatedMetadataField.value = '';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    previewContent.innerHTML = `<div class="text-red-600 font-bold p-6 bg-white rounded-2xl border border-red-100 text-center text-xs">Connection Error</div>`;
                    generatedStepsField.value = '';
                    generatedMetadataField.value = '';
                })
                .finally(() => {
                    previewBtn.disabled = false;
                    previewBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg><span>Regenerate Steps</span>';
                });
            }

            function displayPreview(steps, metadata = null) {
                let html = '<div class="space-y-3">';
                steps.forEach((step, index) => {
                    html += `
                        <div class="bg-white p-4 rounded-xl border border-indigo-100 flex items-start gap-4">
                            <div class="flex-shrink-0 w-6 h-6 bg-indigo-50 text-indigo-600 rounded flex items-center justify-center text-[10px] font-black">${index + 1}</div>
                            <div class="flex-grow">
                                <div class="text-[10px] font-black text-gray-900 uppercase tracking-widest mb-1">${step.action || 'Action'}</div>
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
                if (step.url) parts.push(`path: ${step.url}`);
                if (step.selector) parts.push(`el: ${step.selector}`);
                if (step.value) parts.push(`val: ${step.value}`);
                return parts.join(' | ');
            }

            previewBtn.addEventListener('click', generatePreview);
            regenerateBtn.addEventListener('click', generatePreview);
        });
    </script>
</x-dashboard-layout>
