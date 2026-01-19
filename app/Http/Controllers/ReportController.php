<?php

namespace App\Http\Controllers;

use App\Models\Website;
use App\Models\TestRun;
use App\Models\TestDefinition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Show comprehensive reports dashboard with analytics.
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();
        
        // Get test runs query - filter by user unless admin
        $testRunsQuery = TestRun::query()
            ->whereHas('testCase.testDefinition.website', function($query) use ($user) {
                if (!$user->isAdmin()) {
                    $query->where('user_id', $user->id);
                }
            });

        // Calculate success rate over time (last 30 days)
        $successRateData = $this->getSuccessRateOverTime($testRunsQuery);
        
        // Get most tested websites
        $mostTestedWebsites = $this->getMostTestedWebsites($user);
        
        // Prepare chart data for most tested websites
        $mostTestedLabels = $mostTestedWebsites->map(function($item) {
            return strlen($item->url) > 30 ? substr($item->url, 0, 30) . '...' : $item->url;
        })->toArray();
        $mostTestedCounts = $mostTestedWebsites->pluck('test_count')->toArray();
        
        // Get test execution trends (last 7 days)
        $executionTrends = $this->getExecutionTrends($testRunsQuery);
        
        // Overall statistics
        $totalTests = $testRunsQuery->count();
        $passedTests = $testRunsQuery->where('result', 'pass')->count();
        $failedTests = $testRunsQuery->where('result', 'fail')->count();
        $pendingTests = $testRunsQuery->whereNull('result')->count();
        $successRate = $totalTests > 0 ? round(($passedTests / $totalTests) * 100, 1) : 0;
        
        // Recent test runs
        $recentTestRuns = $testRunsQuery->with(['testCase.testDefinition.website'])
            ->latest('executed_at')
            ->limit(10)
            ->get();

        return view('reports.dashboard', compact(
            'successRateData',
            'mostTestedWebsites',
            'mostTestedLabels',
            'mostTestedCounts',
            'executionTrends',
            'totalTests',
            'passedTests',
            'failedTests',
            'pendingTests',
            'successRate',
            'recentTestRuns'
        ));
    }

    /**
     * Show report for a specific website.
     */
    public function index(Request $request, Website $website)
    {
        if ($website->user_id !== $request->user()->id && ! $request->user()->isAdmin()) {
            abort(403);
        }

        // Fetch latest test runs via test definitions -> test cases -> test runs
        // Or simpler: Get all test definitions for this website
        $testDefinitions = $website->testDefinitions()->with(['testCases.testRuns' => function($query) {
            $query->latest();
        }])->get();

        return view('reports.index', compact('website', 'testDefinitions'));
    }

    /**
     * Show details of a specific test run.
     */
    public function show(Request $request, TestRun $testRun)
    {
         if ($testRun->testCase->testDefinition->website->user_id !== $request->user()->id && ! $request->user()->isAdmin()) {
            abort(403);
        }
        
        return view('reports.show', compact('testRun'));
    }

    /**
     * Export test run as JSON.
     */
    public function exportJson(Request $request, TestRun $testRun)
    {
        if ($testRun->testCase->testDefinition->website->user_id !== $request->user()->id && ! $request->user()->isAdmin()) {
            abort(403);
        }

        $data = [
            'id' => $testRun->id,
            'test_definition' => $testRun->testCase->testDefinition->description,
            'website' => $testRun->testCase->testDefinition->website->url,
            'status' => $testRun->status,
            'result' => $testRun->result,
            'executed_at' => $testRun->executed_at ? $testRun->executed_at->toIso8601String() : null,
            'logs' => $testRun->logs,
            'steps' => $testRun->testCase->steps,
            'expected_result' => $testRun->testCase->expected_result,
        ];

        return response()->json($data, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="test-run-' . $testRun->id . '.json"',
        ]);
    }

    /**
     * Export test run as CSV.
     */
    public function exportCsv(Request $request, TestRun $testRun)
    {
        if ($testRun->testCase->testDefinition->website->user_id !== $request->user()->id && ! $request->user()->isAdmin()) {
            abort(403);
        }

        $filename = 'test-run-' . $testRun->id . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($testRun) {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, ['Field', 'Value']);
            
            // Data rows
            fputcsv($file, ['ID', $testRun->id]);
            fputcsv($file, ['Test Definition', $testRun->testCase->testDefinition->description]);
            fputcsv($file, ['Website', $testRun->testCase->testDefinition->website->url]);
            fputcsv($file, ['Status', $testRun->status]);
            fputcsv($file, ['Result', $testRun->result ?? 'Pending']);
            fputcsv($file, ['Executed At', $testRun->executed_at ? $testRun->executed_at->toDateTimeString() : 'Pending']);
            fputcsv($file, ['Expected Result', $testRun->testCase->expected_result]);
            
            // Logs
            fputcsv($file, ['', '']);
            fputcsv($file, ['Logs', '']);
            foreach ($testRun->logs ?? [] as $log) {
                fputcsv($file, ['', $log]);
            }
            
            // Steps
            fputcsv($file, ['', '']);
            fputcsv($file, ['Steps', '']);
            foreach ($testRun->testCase->steps ?? [] as $index => $step) {
                fputcsv($file, ['', 'Step ' . ($index + 1) . ': ' . (is_string($step) ? $step : json_encode($step))]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get success rate over time data.
     */
    private function getSuccessRateOverTime($query)
    {
        $days = 30;
        $data = [];
        
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->startOfDay();
            $endDate = $date->copy()->endOfDay();
            
            $dayRuns = (clone $query)->whereBetween('executed_at', [$date, $endDate])->get();
            $total = $dayRuns->count();
            $passed = $dayRuns->where('result', 'pass')->count();
            
            $data[] = [
                'date' => $date->format('M j'),
                'success_rate' => $total > 0 ? round(($passed / $total) * 100, 1) : 0,
                'total' => $total,
                'passed' => $passed,
            ];
        }
        
        return $data;
    }

    /**
     * Get most tested websites.
     */
    private function getMostTestedWebsites($user)
    {
        $query = Website::query()
            ->select('websites.id', 'websites.url', DB::raw('COUNT(test_runs.id) as test_count'))
            ->join('test_definitions', 'test_definitions.website_id', '=', 'websites.id')
            ->join('test_cases', 'test_cases.test_definition_id', '=', 'test_definitions.id')
            ->join('test_runs', 'test_runs.test_case_id', '=', 'test_cases.id')
            ->groupBy('websites.id', 'websites.url')
            ->orderBy('test_count', 'desc')
            ->limit(10);
        
        if (!$user->isAdmin()) {
            $query->where('websites.user_id', $user->id);
        }
        
        return $query->get();
    }

    /**
     * Get test execution trends (last 7 days).
     */
    private function getExecutionTrends($query)
    {
        $days = 7;
        $data = [];
        
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->startOfDay();
            $endDate = $date->copy()->endOfDay();
            
            $count = (clone $query)->whereBetween('executed_at', [$date, $endDate])->count();
            
            $data[] = [
                'date' => $date->format('M j'),
                'count' => $count,
            ];
        }
        
        return $data;
    }
}
