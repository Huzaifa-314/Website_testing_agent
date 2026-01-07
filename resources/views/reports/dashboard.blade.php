<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Reports & Analytics') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-8">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Tests</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalTests) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Success Rate</p>
                        <p class="text-3xl font-bold text-green-600 mt-2">{{ $successRate }}%</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Passed</p>
                        <p class="text-3xl font-bold text-green-600 mt-2">{{ number_format($passedTests) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Failed</p>
                        <p class="text-3xl font-bold text-red-600 mt-2">{{ number_format($failedTests) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Success Rate Over Time -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Test Success Rate Over Time</h3>
                <div class="h-[300px]">
                    <canvas id="successRateChart"></canvas>
                </div>
            </div>

            <!-- Test Execution Trends -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Test Execution Trends (Last 7 Days)</h3>
                <div class="h-[300px]">
                    <canvas id="executionTrendsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Most Tested Websites -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Most Tested Websites</h3>
            @if($mostTestedWebsites->isEmpty())
                <p class="text-gray-500 text-center py-8">No test data available</p>
            @else
                <div class="h-[300px]">
                    <canvas id="mostTestedChart"></canvas>
                </div>
            @endif
        </div>

        <!-- Recent Test Runs -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900">Recent Test Runs</h3>
            </div>
            @if($recentTestRuns->isEmpty())
                <div class="p-12 text-center text-gray-500">No test runs found.</div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($recentTestRuns as $run)
                        <div class="p-6 hover:bg-gray-50 transition">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="flex-shrink-0">
                                        @if($run->result == 'pass')
                                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                        @elseif($run->result == 'fail')
                                            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </div>
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900">{{ $run->testCase->testDefinition->description }}</h4>
                                        <p class="text-xs text-gray-500">
                                            {{ $run->testCase->testDefinition->website->url }} &bull; 
                                            {{ $run->executed_at ? $run->executed_at->diffForHumans() : 'Pending' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="px-2 py-1 rounded-md text-xs font-bold uppercase {{ $run->result == 'pass' ? 'bg-green-100 text-green-800' : ($run->result == 'fail' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ $run->result ?? 'PENDING' }}
                                    </span>
                                    <a href="{{ route('reports.show', $run) }}" class="text-gray-400 hover:text-indigo-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Success Rate Chart
        const successRateCtx = document.getElementById('successRateChart');
        if (successRateCtx) {
            new Chart(successRateCtx, {
                type: 'line',
                data: {
                    labels: @json(array_column($successRateData, 'date')),
                    datasets: [{
                        label: 'Success Rate (%)',
                        data: @json(array_column($successRateData, 'success_rate')),
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        }
                    }
                }
            });
        }

        // Execution Trends Chart
        const executionTrendsCtx = document.getElementById('executionTrendsChart');
        if (executionTrendsCtx) {
            new Chart(executionTrendsCtx, {
                type: 'bar',
                data: {
                    labels: @json(array_column($executionTrends, 'date')),
                    datasets: [{
                        label: 'Tests Executed',
                        data: @json(array_column($executionTrends, 'count')),
                        backgroundColor: 'rgba(99, 102, 241, 0.8)',
                        borderColor: 'rgb(99, 102, 241)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }

        // Most Tested Websites Chart
        const mostTestedCtx = document.getElementById('mostTestedChart');
        if (mostTestedCtx) {
            new Chart(mostTestedCtx, {
                type: 'doughnut',
                data: {
                    labels: @json($mostTestedLabels),
                    datasets: [{
                        data: @json($mostTestedCounts),
                        backgroundColor: [
                            'rgba(99, 102, 241, 0.8)',
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(251, 146, 60, 0.8)',
                            'rgba(236, 72, 153, 0.8)',
                            'rgba(168, 85, 247, 0.8)',
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(14, 165, 233, 0.8)',
                            'rgba(20, 184, 166, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(239, 68, 68, 0.8)',
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right'
                        }
                    }
                }
            });
        }
    </script>
    @endpush
</x-app-layout>

