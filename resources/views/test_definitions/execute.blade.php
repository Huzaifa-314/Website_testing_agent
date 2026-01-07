<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <nav class="flex text-sm text-gray-500 mb-1" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('test-definitions.index') }}" class="inline-flex items-center hover:text-gray-900">
                                Test Definitions
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <a href="{{ route('test-definitions.show', $testDefinition) }}" class="hover:text-gray-900">
                                    {{ Str::limit($testDefinition->description, 50) }}
                                </a>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <span class="ml-1 text-gray-700 font-medium">Test Execution</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    Test Execution
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Desktop: Two-column layout, Mobile: Stacked -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-6">
            <!-- Left Column: Execution Logs (Desktop) / First (Mobile) -->
            <div class="lg:sticky lg:top-6 lg:h-[calc(100vh-3rem)] flex flex-col">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 flex flex-col flex-grow">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center flex-shrink-0">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Execution Logs</h3>
                            <p class="text-sm text-gray-500 mt-1">Combined real-time test execution output</p>
                        </div>
                        <button id="clear-logs-btn" class="text-sm text-gray-600 hover:text-gray-900 border border-gray-300 px-3 py-1 rounded hover:bg-gray-50 transition">
                            Clear
                        </button>
                    </div>
                    <div class="bg-[#1e1e1e] rounded-b-xl shadow-2xl overflow-hidden flex-grow flex flex-col min-h-0">
                        <div class="bg-[#2d2d2d] px-4 py-2 flex items-center gap-2 border-b border-[#3e3e3e] flex-shrink-0">
                            <div class="flex gap-2">
                                <div class="w-3 h-3 rounded-full bg-[#ff5f56]"></div>
                                <div class="w-3 h-3 rounded-full bg-[#ffbd2e]"></div>
                                <div class="w-3 h-3 rounded-full bg-[#27c93f]"></div>
                            </div>
                            <div class="ml-4 text-xs text-gray-400 font-mono">klydos-agent execution-log</div>
                        </div>
                        <div id="logs-container" class="p-6 font-mono text-sm leading-relaxed overflow-x-auto overflow-y-auto flex-grow">
                            <div class="text-gray-500">Waiting for execution to start...</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Progress Information (Desktop) / Second (Mobile) -->
            <div class="space-y-6">
                <!-- Test Definition Info -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">{{ $testDefinition->description }}</h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    Website: <a href="{{ route('websites.show', $testDefinition->website) }}" class="text-indigo-600 hover:text-indigo-800">{{ $testDefinition->website->url }}</a>
                                </p>
                            </div>
                            <div id="status-badge" class="px-4 py-2 rounded-lg text-sm font-bold uppercase tracking-wider bg-gray-100 text-gray-700 border border-gray-200">
                                PENDING
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Overall Progress Bar -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-lg font-bold text-gray-900">Overall Execution Progress</h3>
                            <span id="progress-text" class="text-sm font-semibold text-gray-600">0%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                            <div id="progress-bar" class="bg-indigo-600 h-3 transition-all duration-300 ease-out" style="width: 0%"></div>
                        </div>
                        <div class="mt-2 text-sm text-gray-500">
                            <span id="test-case-info">Test Cases: <span id="completed-count">0</span> / <span id="total-count">{{ $testDefinition->testCases->count() }}</span> completed</span>
                        </div>
                    </div>
                </div>

                <!-- Test Cases -->
                <div id="test-cases-container" class="space-y-4">
                    @foreach($testDefinition->testCases as $testCaseIndex => $testCase)
                        @php
                            $latestTestRun = $testCase->testRuns->first();
                            $steps = $testCase->steps ?? [];
                        @endphp
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 test-case-card" data-test-case-id="{{ $testCase->id }}">
                            <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">Test Case #{{ $testCaseIndex + 1 }}</h3>
                                        <p class="text-sm text-gray-500 mt-1">{{ count($steps) }} steps</p>
                                    </div>
                                    <div class="test-case-status-badge px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider bg-gray-100 text-gray-700 border border-gray-200">
                                        PENDING
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Test Case Progress -->
                            <div class="p-6 border-b border-gray-100">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm text-gray-600">Progress</span>
                                    <span class="test-case-progress-text text-sm font-semibold text-gray-600">0%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                    <div class="test-case-progress-bar bg-indigo-600 h-2 transition-all duration-300 ease-out" style="width: 0%"></div>
                                </div>
                                <div class="mt-2 text-xs text-gray-500">
                                    <span class="test-case-step-info">Step 0 of {{ count($steps) }}</span>
                                </div>
                            </div>

                            <!-- Test Case Steps -->
                            <div class="p-6">
                                <div class="test-case-steps space-y-2">
                                    @foreach($steps as $index => $step)
                                        <div class="step-item flex items-start gap-3 p-3 rounded-lg border border-gray-200 bg-gray-50 transition-all" data-step="{{ $index + 1 }}" data-test-case-id="{{ $testCase->id }}">
                                            <div class="step-icon flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold bg-gray-100 text-gray-500 border border-gray-200">
                                                {{ $index + 1 }}
                                            </div>
                                            <div class="flex-grow">
                                                <div class="font-semibold text-sm text-gray-900 mb-1">
                                                    {{ ucfirst(str_replace('_', ' ', $step['action'] ?? 'Unknown')) }}
                                                </div>
                                                <div class="text-xs text-gray-600 font-mono space-y-1">
                                                    @if(isset($step['url']))
                                                        <div>URL: <span class="text-indigo-600">{{ $step['url'] }}</span></div>
                                                    @endif
                                                    @if(isset($step['selector']))
                                                        <div>Selector: <span class="text-indigo-600">{{ $step['selector'] }}</span></div>
                                                    @endif
                                                    @if(isset($step['value']))
                                                        <div>Value: <span class="text-indigo-600">{{ $step['value'] }}</span></div>
                                                    @endif
                                                </div>
                                                <div class="step-status mt-1 text-xs font-medium"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Actions -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                    <div class="p-6 flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Actions</h3>
                            <p class="text-sm text-gray-500 mt-1">Manage test execution</p>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('test-definitions.show', $testDefinition) }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition">
                                Back to Definition
                            </a>
                            <button id="re-run-btn" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition hidden">
                                Re-run All Tests
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        (function() {
            // Wait for DOM to be ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initSimulation);
            } else {
                initSimulation();
            }

            function initSimulation() {
                // Mock simulation - Pure frontend execution
                const testCasesData = @json($testDefinition->testCases->map(function($testCase) {
                    return [
                        'id' => $testCase->id,
                        'steps' => $testCase->steps ?? [],
                    ];
                })->values()->toArray());
                
                console.log('Test cases data:', testCasesData);
                
                let isCompleted = false;
                let allLogs = [];
                let testCaseStates = {};
                let testCases = Array.isArray(testCasesData) ? testCasesData : [];

                // Elements
                const statusBadge = document.getElementById('status-badge');
                const progressBar = document.getElementById('progress-bar');
                const progressText = document.getElementById('progress-text');
                const completedCount = document.getElementById('completed-count');
                const totalCount = document.getElementById('total-count');
                const logsContainer = document.getElementById('logs-container');
                const reRunBtn = document.getElementById('re-run-btn');
                const clearLogsBtn = document.getElementById('clear-logs-btn');

                if (!logsContainer) {
                    console.error('Logs container not found');
                    return;
                }

                // Update logs function
                function updateLogs(logs) {
                    if (!logsContainer) return;
                    logsContainer.innerHTML = '';
                    logs.forEach(log => {
                        const logLine = document.createElement('div');
                        logLine.className = 'mb-1 flex';
                        
                        const prompt = document.createElement('span');
                        prompt.className = 'text-gray-500 mr-4 select-none';
                        prompt.textContent = '$';
                        
                        const content = document.createElement('span');
                        
                        // Color code based on log type
                        if (log.includes('[ERROR]') || log.includes('FAILED') || log.includes('FAIL')) {
                            content.className = 'text-red-400';
                            content.textContent = log;
                        } else if (log.includes('[STEP') && log.includes('✓')) {
                            // Success checkmark
                            const beforeCheck = log.substring(0, log.indexOf('✓'));
                            const checkSpan = document.createElement('span');
                            checkSpan.className = 'text-green-400 font-bold';
                            checkSpan.textContent = '✓';
                            const afterCheck = log.substring(log.indexOf('✓') + 1);
                            content.className = 'text-gray-300';
                            content.textContent = beforeCheck;
                            content.appendChild(checkSpan);
                            content.appendChild(document.createTextNode(afterCheck));
                        } else if (log.includes('[INFO]')) {
                            content.className = 'text-blue-400';
                            content.textContent = log;
                        } else if (log.includes('[STEP')) {
                            content.className = 'text-yellow-300';
                            content.textContent = log;
                        } else if (log.includes('OK')) {
                            const beforeOk = log.substring(0, log.indexOf('OK'));
                            const okSpan = document.createElement('span');
                            okSpan.className = 'text-green-400 font-bold';
                            okSpan.textContent = 'OK';
                            content.className = 'text-gray-300';
                            content.textContent = beforeOk;
                            content.appendChild(okSpan);
                        } else {
                            content.className = 'text-gray-300';
                            content.textContent = log;
                        }
                        
                        logLine.appendChild(prompt);
                        logLine.appendChild(content);
                        logsContainer.appendChild(logLine);
                    });
                    
                    // Auto-scroll to bottom
                    logsContainer.scrollTop = logsContainer.scrollHeight;
                }

                // Add log to container
                function addLog(log) {
                    allLogs.push(log);
                    updateLogs(allLogs);
                }

                // Initialize test case states
                if (!testCases || testCases.length === 0) {
                    addLog('[ERROR] No test cases found');
                    if (statusBadge) {
                        statusBadge.className = 'px-4 py-2 rounded-lg text-sm font-bold uppercase tracking-wider bg-red-100 text-red-700 border border-red-200';
                        statusBadge.textContent = 'ERROR';
                    }
                    return;
                }

                testCases.forEach((testCase, index) => {
                    if (!testCase || !testCase.id) return;
                    testCaseStates[testCase.id] = {
                        currentStep: 0,
                        totalSteps: (testCase.steps || []).length,
                        status: 'pending',
                        result: null,
                        logs: []
                    };
                });

                // Initialize UI
                if (totalCount) totalCount.textContent = testCases.length;
                if (statusBadge) {
                    statusBadge.className = 'px-4 py-2 rounded-lg text-sm font-bold uppercase tracking-wider bg-blue-100 text-blue-700 border border-blue-200';
                    statusBadge.textContent = 'RUNNING';
                }

                // Update test case UI
                function updateTestCaseUI(testCaseId, state) {
                const testCaseCard = document.querySelector(`.test-case-card[data-test-case-id="${testCaseId}"]`);
                if (!testCaseCard) return;

                const statusBadgeEl = testCaseCard.querySelector('.test-case-status-badge');
                const progressBarEl = testCaseCard.querySelector('.test-case-progress-bar');
                const progressTextEl = testCaseCard.querySelector('.test-case-progress-text');
                const stepInfoEl = testCaseCard.querySelector('.test-case-step-info');

                // Update status badge
                const statusColors = {
                    'pending': 'bg-gray-100 text-gray-700 border-gray-200',
                    'running': 'bg-blue-100 text-blue-700 border-blue-200',
                    'completed': state.result === 'pass' ? 'bg-green-100 text-green-700 border-green-200' : 'bg-red-100 text-red-700 border-red-200',
                };
                const statusText = {
                    'pending': 'PENDING',
                    'running': 'RUNNING',
                    'completed': state.result === 'pass' ? 'PASS' : 'FAIL',
                };
                    if (statusBadgeEl) {
                        statusBadgeEl.className = `px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider border ${statusColors[state.status] || statusColors.pending}`;
                        statusBadgeEl.textContent = statusText[state.status] || 'PENDING';
                    }

                    // Update progress
                    const progress = state.totalSteps > 0 ? Math.round((state.currentStep / state.totalSteps) * 100) : 0;
                    if (progressBarEl) progressBarEl.style.width = progress + '%';
                    if (progressTextEl) progressTextEl.textContent = progress + '%';
                    if (stepInfoEl) stepInfoEl.textContent = `Step ${state.currentStep} of ${state.totalSteps}`;

                // Update step indicators
                updateTestCaseSteps(testCaseCard, state.currentStep, state.status, state.result);
            }

                // Update overall progress
                function updateOverallProgress() {
                let totalSteps = 0;
                let completedSteps = 0;
                let completedCases = 0;
                let allDone = true;
                let hasFailure = false;

                testCases.forEach(testCase => {
                    const state = testCaseStates[testCase.id];
                    totalSteps += state.totalSteps;
                    completedSteps += state.currentStep;
                    if (state.status === 'completed') {
                        completedCases++;
                        if (state.result === 'fail') hasFailure = true;
                    } else {
                        allDone = false;
                    }
                });

                const overallProgress = totalSteps > 0 ? Math.round((completedSteps / totalSteps) * 100) : 0;
                if (progressBar) progressBar.style.width = overallProgress + '%';
                if (progressText) progressText.textContent = overallProgress + '%';
                if (completedCount) completedCount.textContent = completedCases;

                if (allDone) {
                    isCompleted = true;
                    if (statusBadge) {
                        statusBadge.className = `px-4 py-2 rounded-lg text-sm font-bold uppercase tracking-wider border ${hasFailure ? 'bg-red-100 text-red-700 border-red-200' : 'bg-green-100 text-green-700 border-green-200'}`;
                        statusBadge.textContent = hasFailure ? 'FAIL' : 'PASS';
                    }
                    if (reRunBtn) {
                        reRunBtn.classList.remove('hidden');
                    }
                }
            }

                // Update step indicators for a test case
                function updateTestCaseSteps(testCaseCard, completedSteps, status, result) {
                const stepItems = testCaseCard.querySelectorAll('.step-item');
                const currentStepNum = completedSteps + 1;

                stepItems.forEach((item, index) => {
                    const stepNum = index + 1;
                    const icon = item.querySelector('.step-icon');
                    const statusDiv = item.querySelector('.step-status');

                    if (stepNum <= completedSteps) {
                        // Completed step
                        item.classList.remove('border-gray-200', 'bg-gray-50', 'border-blue-200', 'bg-blue-50', 'border-red-200', 'bg-red-50');
                        item.classList.add('border-green-200', 'bg-green-50');
                        icon.classList.remove('bg-gray-100', 'text-gray-500', 'border-gray-200', 'bg-blue-100', 'text-blue-700', 'border-blue-300', 'bg-red-100', 'text-red-700', 'border-red-300', 'animate-pulse');
                        icon.classList.add('bg-green-100', 'text-green-700', 'border-green-300');
                        icon.innerHTML = '✓';
                        statusDiv.textContent = 'Completed';
                        statusDiv.className = 'mt-1 text-xs font-medium text-green-700';
                    } else if (stepNum === currentStepNum && status === 'running') {
                        // Current step being executed
                        item.classList.remove('border-gray-200', 'bg-gray-50', 'border-green-200', 'bg-green-50', 'border-red-200', 'bg-red-50');
                        item.classList.add('border-blue-200', 'bg-blue-50');
                        icon.classList.remove('bg-gray-100', 'text-gray-500', 'border-gray-200', 'bg-green-100', 'text-green-700', 'border-green-300', 'bg-red-100', 'text-red-700', 'border-red-300');
                        icon.classList.add('bg-blue-100', 'text-blue-700', 'border-blue-300', 'animate-pulse');
                        icon.textContent = stepNum;
                        statusDiv.textContent = 'Running...';
                        statusDiv.className = 'mt-1 text-xs font-medium text-blue-700';
                    } else if (stepNum === currentStepNum && status === 'completed' && result === 'fail') {
                        // Failed step
                        item.classList.remove('border-gray-200', 'bg-gray-50', 'border-green-200', 'bg-green-50', 'border-blue-200', 'bg-blue-50');
                        item.classList.add('border-red-200', 'bg-red-50');
                        icon.classList.remove('bg-gray-100', 'text-gray-500', 'border-gray-200', 'bg-green-100', 'text-green-700', 'border-green-300', 'bg-blue-100', 'text-blue-700', 'border-blue-300', 'animate-pulse');
                        icon.classList.add('bg-red-100', 'text-red-700', 'border-red-300');
                        icon.innerHTML = '✗';
                        statusDiv.textContent = 'Failed';
                        statusDiv.className = 'mt-1 text-xs font-medium text-red-700';
                    } else {
                        // Pending step
                        item.classList.remove('border-green-200', 'bg-green-50', 'border-blue-200', 'bg-blue-50', 'border-red-200', 'bg-red-50');
                        item.classList.add('border-gray-200', 'bg-gray-50');
                        icon.classList.remove('bg-green-100', 'text-green-700', 'border-green-300', 'bg-blue-100', 'text-blue-700', 'border-blue-300', 'bg-red-100', 'text-red-700', 'border-red-300', 'animate-pulse');
                        icon.classList.add('bg-gray-100', 'text-gray-500', 'border-gray-200');
                        icon.textContent = stepNum;
                        statusDiv.textContent = '';
                        statusDiv.className = 'mt-1 text-xs font-medium';
                    }
                });
                }

                // Simulate executing a step
                async function executeStep(testCaseId, stepIndex) {
                    const testCase = testCases.find(tc => tc.id === testCaseId);
                    if (!testCase) return;
                    const state = testCaseStates[testCaseId];
                    if (!state) return;
                    const step = testCase.steps[stepIndex];
                    if (!step) return;
                    const stepNum = stepIndex + 1;
                    const action = step.action || 'unknown';

                    // Update state to running
                    state.status = 'running';
                    state.currentStep = stepIndex;
                    updateTestCaseUI(testCaseId, state);
                    updateOverallProgress();

                    // Generate realistic logs based on action
                    const delay = getActionDelay(action);
                    
                    addLog(`[STEP ${stepNum}] Executing: ${ucfirst(action.replace('_', ' '))}`);

                    if (action === 'visit') {
                        addLog(`[STEP ${stepNum}] Navigating to: ${step.url || 'unknown'}`);
                        await sleep(delay * 0.3);
                        addLog(`[STEP ${stepNum}] Waiting for page load...`);
                        await sleep(delay * 0.4);
                        addLog(`[STEP ${stepNum}] Page loaded successfully`);
                        await sleep(delay * 0.3);
                        addLog(`[STEP ${stepNum}] ✓ URL visited successfully`);
                    } else if (action === 'type') {
                        addLog(`[STEP ${stepNum}] Locating element: ${step.selector || 'unknown'}`);
                        await sleep(delay * 0.2);
                        addLog(`[STEP ${stepNum}] Element found, focusing...`);
                        await sleep(delay * 0.2);
                        addLog(`[STEP ${stepNum}] Typing text: '${step.value || ''}'`);
                        await sleep(delay * 0.4);
                        addLog(`[STEP ${stepNum}] Text entered successfully`);
                        await sleep(delay * 0.2);
                        addLog(`[STEP ${stepNum}] ✓ Typing completed`);
                    } else if (action === 'click') {
                        addLog(`[STEP ${stepNum}] Locating element: ${step.selector || 'unknown'}`);
                        await sleep(delay * 0.3);
                        addLog(`[STEP ${stepNum}] Element found, scrolling into view...`);
                        await sleep(delay * 0.2);
                        addLog(`[STEP ${stepNum}] Clicking element...`);
                        await sleep(delay * 0.3);
                        addLog(`[STEP ${stepNum}] Waiting for action to complete...`);
                        await sleep(delay * 0.2);
                        addLog(`[STEP ${stepNum}] ✓ Click action completed`);
                    } else if (action === 'assert_url') {
                        addLog(`[STEP ${stepNum}] Getting current page URL...`);
                        await sleep(delay * 0.3);
                        addLog(`[STEP ${stepNum}] Current URL: ${step.value || 'unknown'}`);
                        await sleep(delay * 0.2);
                        addLog(`[STEP ${stepNum}] Asserting URL matches expected value...`);
                        await sleep(delay * 0.3);
                        addLog(`[STEP ${stepNum}] ✓ URL assertion passed`);
                    } else if (action === 'assert_text') {
                        addLog(`[STEP ${stepNum}] Locating element: ${step.selector || 'body'}`);
                        await sleep(delay * 0.3);
                        addLog(`[STEP ${stepNum}] Extracting text content...`);
                        await sleep(delay * 0.2);
                        addLog(`[STEP ${stepNum}] Asserting text contains: '${step.value || ''}'`);
                        await sleep(delay * 0.3);
                        addLog(`[STEP ${stepNum}] ✓ Text assertion passed`);
                    } else if (action === 'assert_status') {
                        addLog(`[STEP ${stepNum}] Checking HTTP status code...`);
                        await sleep(delay * 0.4);
                        addLog(`[STEP ${stepNum}] Current status: ${step.value || '200'}`);
                        await sleep(delay * 0.2);
                        addLog(`[STEP ${stepNum}] Asserting status matches expected value...`);
                        await sleep(delay * 0.2);
                        addLog(`[STEP ${stepNum}] ✓ Status assertion passed`);
                    } else {
                        addLog(`[STEP ${stepNum}] Executing action: ${action}`);
                        await sleep(delay);
                        addLog(`[STEP ${stepNum}] ✓ Action completed`);
                    }

                    // Update state
                    state.currentStep = stepNum;
                    updateTestCaseUI(testCaseId, state);
                    updateOverallProgress();
                }

                // Execute a test case
                async function executeTestCase(testCaseId) {
                    const testCase = testCases.find(tc => tc.id === testCaseId);
                    if (!testCase) return;
                    const state = testCaseStates[testCaseId];
                    if (!state) return;

                    if (state.logs.length === 0) {
                        addLog(`[INFO] Initializing browser session...`);
                        addLog(`[INFO] Browser: Chrome/Headless (simulated)`);
                        addLog(`[INFO] Starting execution for Test Case #${testCaseId}`);
                        addLog(`[INFO] Total steps: ${state.totalSteps}`);
                        state.logs = ['initialized'];
                    }

                    // Execute each step
                    for (let i = 0; i < testCase.steps.length; i++) {
                        await executeStep(testCaseId, i);
                    }

                    // Mark as completed
                    state.status = 'completed';
                    state.result = 'pass';
                    addLog(`[INFO] All steps completed successfully`);
                    addLog(`[INFO] Execution finished. Result: PASS`);
                    updateTestCaseUI(testCaseId, state);
                    updateOverallProgress();
                }

                // Helper functions
                function sleep(ms) {
                    return new Promise(resolve => setTimeout(resolve, ms));
                }

                function getActionDelay(action) {
                    const delays = {
                        'visit': 2000,
                        'click': 1500,
                        'type': 1000,
                        'assert_url': 800,
                        'assert_text': 800,
                        'assert_status': 800,
                    };
                    const baseDelay = delays[action] || 1000;
                    // Add randomness ±30%
                    return baseDelay * (0.7 + Math.random() * 0.6);
                }

                function ucfirst(str) {
                    return str.charAt(0).toUpperCase() + str.slice(1);
                }

                // Clear logs button
                if (clearLogsBtn) {
                    clearLogsBtn.addEventListener('click', () => {
                        allLogs = [];
                        updateLogs([]);
                    });
                }

                // Re-run button
                if (reRunBtn) {
                    reRunBtn.addEventListener('click', () => {
                        location.reload();
                    });
                }

                // Start simulation automatically
                addLog('[INFO] Test execution simulation starting...');
                setTimeout(() => {
                    // Execute all test cases (sequentially for demo)
                    (async () => {
                        for (const testCase of testCases) {
                            if (testCase && testCase.id) {
                                await executeTestCase(testCase.id);
                                // Small delay between test cases
                                await sleep(500);
                            }
                        }
                    })();
                }, 1000);
            }
        })();
    </script>
    @endpush
</x-app-layout>
