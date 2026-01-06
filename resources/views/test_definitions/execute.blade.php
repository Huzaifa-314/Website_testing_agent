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

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
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

        <!-- Real-time Logs -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Execution Logs</h3>
                    <p class="text-sm text-gray-500 mt-1">Combined real-time test execution output</p>
                </div>
                <button id="clear-logs-btn" class="text-sm text-gray-600 hover:text-gray-900 border border-gray-300 px-3 py-1 rounded hover:bg-gray-50 transition">
                    Clear
                </button>
            </div>
            <div class="bg-[#1e1e1e] rounded-b-xl shadow-2xl overflow-hidden">
                <div class="bg-[#2d2d2d] px-4 py-2 flex items-center gap-2 border-b border-[#3e3e3e]">
                    <div class="flex gap-2">
                        <div class="w-3 h-3 rounded-full bg-[#ff5f56]"></div>
                        <div class="w-3 h-3 rounded-full bg-[#ffbd2e]"></div>
                        <div class="w-3 h-3 rounded-full bg-[#27c93f]"></div>
                    </div>
                    <div class="ml-4 text-xs text-gray-400 font-mono">klydos-agent execution-log</div>
                </div>
                <div id="logs-container" class="p-6 font-mono text-sm leading-relaxed overflow-x-auto max-h-96 overflow-y-auto">
                    <div class="text-gray-500">Waiting for execution to start...</div>
                </div>
            </div>
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

    @push('scripts')
    <script>
        (function() {
            const testDefinitionId = {{ $testDefinition->id }};
            const testRunId = {{ $testRun->id }};
            const progressUrl = '{{ route("test-definitions.progress", ["testDefinition" => $testDefinition, "testRun" => $testRun]) }}';
            
            let pollInterval = null;
            let isCompleted = false;
            let allLogs = [];

            // Elements
            const statusBadge = document.getElementById('status-badge');
            const progressBar = document.getElementById('progress-bar');
            const progressText = document.getElementById('progress-text');
            const completedCount = document.getElementById('completed-count');
            const totalCount = document.getElementById('total-count');
            const logsContainer = document.getElementById('logs-container');
            const reRunBtn = document.getElementById('re-run-btn');
            const clearLogsBtn = document.getElementById('clear-logs-btn');

            // Update UI based on progress data
            function updateUI(data) {
                // Update overall status badge
                const statusColors = {
                    'pending': 'bg-gray-100 text-gray-700 border-gray-200',
                    'running': 'bg-blue-100 text-blue-700 border-blue-200',
                    'completed': data.overall_result === 'pass' ? 'bg-green-100 text-green-700 border-green-200' : (data.overall_result === 'fail' ? 'bg-red-100 text-red-700 border-red-200' : 'bg-gray-100 text-gray-700 border-gray-200'),
                    'failed': 'bg-red-100 text-red-700 border-red-200'
                };
                const statusText = {
                    'pending': 'PENDING',
                    'running': 'RUNNING',
                    'completed': data.overall_result === 'pass' ? 'PASS' : (data.overall_result === 'fail' ? 'FAIL' : 'COMPLETED'),
                    'failed': 'FAILED'
                };
                statusBadge.className = `px-4 py-2 rounded-lg text-sm font-bold uppercase tracking-wider border ${statusColors[data.overall_status] || statusColors.pending}`;
                statusBadge.textContent = statusText[data.overall_status] || 'PENDING';

                // Update overall progress bar
                const progress = data.overall_progress || 0;
                progressBar.style.width = progress + '%';
                progressText.textContent = progress + '%';
                completedCount.textContent = data.completed_test_cases || 0;
                totalCount.textContent = data.total_test_cases || 0;

                // Update each test case
                if (data.test_runs && data.test_runs.length > 0) {
                    data.test_runs.forEach(testRunData => {
                        updateTestCaseUI(testRunData);
                    });
                }

                // Combine and update logs
                const combinedLogs = [];
                if (data.test_runs) {
                    data.test_runs.forEach(testRunData => {
                        if (testRunData.logs && testRunData.logs.length > 0) {
                            combinedLogs.push(...testRunData.logs);
                        }
                    });
                }
                if (combinedLogs.length > 0) {
                    updateLogs(combinedLogs);
                }

                // Handle completion
                if (data.overall_status === 'completed') {
                    isCompleted = true;
                    if (pollInterval) {
                        clearInterval(pollInterval);
                        pollInterval = null;
                    }
                    reRunBtn.classList.remove('hidden');
                }
            }

            // Update individual test case UI
            function updateTestCaseUI(testRunData) {
                const testCaseCard = document.querySelector(`.test-case-card[data-test-case-id="${testRunData.test_case_id}"]`);
                if (!testCaseCard) return;

                const statusBadgeEl = testCaseCard.querySelector('.test-case-status-badge');
                const progressBarEl = testCaseCard.querySelector('.test-case-progress-bar');
                const progressTextEl = testCaseCard.querySelector('.test-case-progress-text');
                const stepInfoEl = testCaseCard.querySelector('.test-case-step-info');
                const stepsContainer = testCaseCard.querySelector('.test-case-steps');

                // Update status badge
                const statusColors = {
                    'pending': 'bg-gray-100 text-gray-700 border-gray-200',
                    'running': 'bg-blue-100 text-blue-700 border-blue-200',
                    'completed': testRunData.result === 'pass' ? 'bg-green-100 text-green-700 border-green-200' : (testRunData.result === 'fail' ? 'bg-red-100 text-red-700 border-red-200' : 'bg-gray-100 text-gray-700 border-gray-200'),
                };
                const statusText = {
                    'pending': 'PENDING',
                    'running': 'RUNNING',
                    'completed': testRunData.result === 'pass' ? 'PASS' : (testRunData.result === 'fail' ? 'FAIL' : 'COMPLETED'),
                };
                statusBadgeEl.className = `px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider border ${statusColors[testRunData.status] || statusColors.pending}`;
                statusBadgeEl.textContent = statusText[testRunData.status] || 'PENDING';

                // Update progress
                const progress = testRunData.progress_percent || 0;
                progressBarEl.style.width = progress + '%';
                progressTextEl.textContent = progress + '%';
                const currentStepNum = (testRunData.current_step || 0) + 1;
                stepInfoEl.textContent = `Step ${currentStepNum} of ${testRunData.total_steps || 0}`;

                // Update step indicators
                updateTestCaseSteps(testCaseCard, testRunData.current_step || 0, testRunData.status, testRunData.result);
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

            // Update logs
            function updateLogs(logs) {
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

            // Poll for progress
            function pollProgress() {
                fetch(progressUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    updateUI(data);
                    
                    // Continue polling if not completed
                    if (data.overall_status !== 'completed' && !isCompleted) {
                        // Poll more frequently during execution to catch progress updates
                        // The backend will naturally throttle execution with delays
                        setTimeout(pollProgress, 800); // Poll every 800ms
                    }
                })
                .catch(error => {
                    console.error('Error polling progress:', error);
                    if (!isCompleted) {
                        setTimeout(pollProgress, 2000); // Retry after 2s on error
                    }
                });
            }

            // Clear logs button
            clearLogsBtn.addEventListener('click', () => {
                logsContainer.innerHTML = '<div class="text-gray-500">Logs cleared</div>';
            });

            // Re-run button
            reRunBtn.addEventListener('click', () => {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("test-definitions.run", $testDefinition) }}';
                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                const asyncInput = document.createElement('input');
                asyncInput.type = 'hidden';
                asyncInput.name = 'async';
                asyncInput.value = '1';
                form.appendChild(csrf);
                form.appendChild(asyncInput);
                document.body.appendChild(form);
                form.submit();
            });

            // Start polling
            pollProgress();
        })();
    </script>
    @endpush
</x-app-layout>
