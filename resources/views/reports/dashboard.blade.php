<x-dashboard-layout>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-4xl font-black tracking-tight mb-2 bg-clip-text text-transparent bg-gradient-to-r from-gray-900 via-indigo-900 to-indigo-600">Reports & Analytics</h1>
            <p class="text-gray-500 font-medium">Analyze your test results and performance trends.</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-50 p-6 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Tests</p>
                    <p class="text-3xl font-black text-gray-900 mt-1">{{ number_format($totalTests) }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-50 p-6 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Success Rate</p>
                    <p class="text-3xl font-black text-emerald-600 mt-1">{{ $successRate }}%</p>
                </div>
                <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-50 p-6 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Passed</p>
                    <p class="text-3xl font-black text-emerald-600 mt-1">{{ number_format($passedTests) }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-50 p-6 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Failed</p>
                    <p class="text-3xl font-black text-red-600 mt-1">{{ number_format($failedTests) }}</p>
                </div>
                <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center text-red-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Success Rate Over Time -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-50 p-8 hover:shadow-xl transition-all duration-500">
            <h3 class="text-lg font-black text-gray-900 mb-6">Success Rate Over Time</h3>
            <div class="h-[300px]">
                <canvas id="successRateChart"></canvas>
            </div>
        </div>

        <!-- Test Execution Trends -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-50 p-8 hover:shadow-xl transition-all duration-500">
            <h3 class="text-lg font-black text-gray-900 mb-6">Last 7 Days Activity</h3>
            <div class="h-[300px]">
                <canvas id="executionTrendsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Most Tested Websites -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-50 p-8 mb-8 hover:shadow-xl transition-all duration-500">
        <h3 class="text-lg font-black text-gray-900 mb-6">Website Distribution</h3>
        @if($mostTestedWebsites->isEmpty())
            <div class="p-12 text-center text-gray-400 font-bold uppercase tracking-widest">No data available</div>
        @else
            <div class="h-[300px]">
                <canvas id="mostTestedChart"></canvas>
            </div>
        @endif
    </div>

    <!-- Recent Test Runs -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-50 overflow-hidden hover:shadow-xl transition-all duration-500">
        <div class="p-8 border-b border-gray-50 bg-gray-50/30">
            <h3 class="text-lg font-black text-gray-900">Recent Test Results</h3>
        </div>
        @if($recentTestRuns->isEmpty())
            <div class="p-16 text-center text-gray-400 font-bold uppercase tracking-widest">No recent runs found.</div>
        @else
            <div class="divide-y divide-gray-50">
                @foreach($recentTestRuns as $run)
                    <div class="p-8 hover:bg-gray-50 transition-all duration-300 group">
                        <div class="flex items-center justify-between gap-6">
                            <div class="flex items-center gap-6 min-w-0">
                                <div class="flex-shrink-0">
                                    @if($run->result == 'pass')
                                        <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-500 group-hover:scale-110 transition-transform">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                    @elseif($run->result == 'fail')
                                        <div class="w-14 h-14 rounded-2xl bg-red-50 flex items-center justify-center text-red-500 group-hover:scale-110 transition-transform">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </div>
                                    @else
                                        <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400 group-hover:scale-110 transition-transform">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-lg font-black text-gray-900 truncate mb-1">{{ $run->testCase->testDefinition->description }}</h4>
                                    <p class="text-sm font-medium text-gray-400 truncate">
                                        {{ parse_url($run->testCase->testDefinition->website->url, PHP_URL_HOST) }} &nbsp;•&nbsp; 
                                        {{ $run->executed_at ? $run->executed_at->diffForHumans() : 'Pending' }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-6 flex-shrink-0">
                                <div class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-[0.2em] {{ $run->result == 'pass' ? 'bg-emerald-50 text-emerald-600' : ($run->result == 'fail' ? 'bg-red-50 text-red-600' : 'bg-gray-50 text-gray-600') }}">
                                    {{ $run->result ?? 'PENDING' }}
                                </div>
                                <a href="{{ route('reports.show', $run) }}" class="p-3 bg-white border border-gray-100 rounded-xl text-gray-400 hover:text-indigo-600 hover:border-indigo-100 hover:shadow-lg transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
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
                        borderColor: 'rgb(99, 102, 241)',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: 'rgb(99, 102, 241)',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            grid: { color: 'rgba(0,0,0,0.02)' },
                            ticks: { callback: v => v + '%' }
                        },
                        x: { grid: { display: false } }
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
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        borderColor: 'rgb(99, 102, 241)',
                        borderWidth: 2,
                        borderRadius: 12,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.02)' }, ticks: { stepSize: 1 } },
                        x: { grid: { display: false } }
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
                         ],
                        borderWidth: 0,
                        hoverOffset: 20
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: { position: 'right', labels: { usePointStyle: true, padding: 20, font: { weight: 'bold' } } }
                    }
                }
            });
        }
    </script>
    @endpush
</x-dashboard-layout>
