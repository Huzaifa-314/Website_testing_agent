<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <span>{{ __('Admin Dashboard') }}</span>
            <div class="flex gap-2">
                <a href="{{ route('admin.dashboard.export', ['date_from' => $stats['date_from'], 'date_to' => $stats['date_to']]) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export CSV
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Date Range Filter -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <form method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[200px]">
                    <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                    <input type="date" 
                           id="date_from" 
                           name="date_from" 
                           value="{{ $stats['date_from'] }}" 
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                    <input type="date" 
                           id="date_to" 
                           name="date_to" 
                           value="{{ $stats['date_to'] }}" 
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Filter
                    </button>
                </div>
                <div>
                    <a href="{{ route('admin.dashboard') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Overall Stats - Clickable -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
            <a href="{{ route('admin.users.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition-shadow cursor-pointer">
                <div class="text-gray-500 text-sm">Total Users</div>
                <div class="font-bold text-2xl text-blue-600">{{ $stats['users'] }}</div>
            </a>
            <a href="{{ route('admin.websites.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition-shadow cursor-pointer">
                <div class="text-gray-500 text-sm">Total Websites</div>
                <div class="font-bold text-2xl text-green-600">{{ $stats['websites'] }}</div>
            </a>
            <a href="{{ route('admin.test-definitions.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition-shadow cursor-pointer">
                <div class="text-gray-500 text-sm">Test Definitions</div>
                <div class="font-bold text-2xl text-indigo-600">{{ $stats['test_definitions'] }}</div>
            </a>
            <a href="{{ route('admin.test-runs.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition-shadow cursor-pointer">
                <div class="text-gray-500 text-sm">Total Test Runs</div>
                <div class="font-bold text-2xl text-purple-600">{{ $stats['test_runs'] }}</div>
                @if($stats['date_from'] !== $stats['default_date_from'] || $stats['date_to'] !== $stats['default_date_to'])
                    <div class="text-xs text-gray-400 mt-1">Filtered: {{ $stats['filtered_test_runs'] }}</div>
                @endif
            </a>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-500 text-sm">Success Rate</div>
                <div class="font-bold text-2xl {{ $stats['success_rate'] >= 80 ? 'text-green-600' : ($stats['success_rate'] >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                    {{ $stats['success_rate'] }}%
                </div>
                @if($stats['date_from'] !== $stats['default_date_from'] || $stats['date_to'] !== $stats['default_date_to'])
                    <div class="text-xs text-gray-400 mt-1">Filtered: {{ $stats['filtered_success_rate'] }}%</div>
                @endif
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Success Rate Trend Chart -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Success Rate Trend</h3>
                <div style="height: 300px; position: relative;">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>

            <!-- Success/Failure Distribution Pie Chart -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Test Results Distribution</h3>
                <div style="height: 300px; position: relative;">
                    <canvas id="distributionChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Activity with Pagination -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h3 class="text-lg font-bold mb-4">Recent Test Executions</h3>
                
                @if($stats['recent_runs']->isEmpty())
                    <p class="text-gray-500">No activity found for the selected date range.</p>
                @else
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Website</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Result</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($stats['recent_runs'] as $run)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $run->executed_at->format('Y-m-d H:i:s') }}
                                        <div class="text-xs text-gray-400">{{ $run->executed_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <a href="{{ route('admin.websites.show', $run->testCase->testDefinition->website) }}" class="text-indigo-600 hover:text-indigo-900">
                                            {{ $run->testCase->testDefinition->website->url }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <a href="{{ route('admin.users.show', $run->testCase->testDefinition->website->user) }}" class="text-indigo-600 hover:text-indigo-900">
                                            {{ $run->testCase->testDefinition->website->user->name }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('admin.test-runs.show', $run) }}" class="block">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $run->result === 'pass' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ strtoupper($run->result) }}
                                            </span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $stats['recent_runs']->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Trend Chart - Line Chart
        const trendCtx = document.getElementById('trendChart');
        if (trendCtx) {
            const trendData = @json($stats['trend_data']);
            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: trendData.map(d => d.label),
                    datasets: [
                        {
                            label: 'Success Rate (%)',
                            data: trendData.map(d => d.success_rate),
                            borderColor: 'rgb(34, 197, 94)',
                            backgroundColor: 'rgba(34, 197, 94, 0.1)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Total Runs',
                            data: trendData.map(d => d.total),
                            borderColor: 'rgb(99, 102, 241)',
                            backgroundColor: 'rgba(99, 102, 241, 0.1)',
                            tension: 0.4,
                            yAxisID: 'y1',
                            fill: false
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            title: {
                                display: true,
                                text: 'Success Rate (%)'
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Total Runs'
                            },
                            grid: {
                                drawOnChartArea: false
                            }
                        }
                    }
                }
            });
        }

        // Distribution Chart - Pie Chart
        const distributionCtx = document.getElementById('distributionChart');
        if (distributionCtx) {
            const distributionData = @json($stats['distribution_data']);
            new Chart(distributionCtx, {
                type: 'pie',
                data: {
                    labels: ['Pass', 'Fail'],
                    datasets: [{
                        data: [distributionData.pass, distributionData.fail],
                        backgroundColor: [
                            'rgb(34, 197, 94)',
                            'rgb(239, 68, 68)'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return label + ': ' + value + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>
    @endpush
</x-admin-layout>
