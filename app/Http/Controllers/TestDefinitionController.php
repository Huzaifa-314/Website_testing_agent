<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Website;
use App\Models\TestDefinition;
use App\Models\TestDefinitionTemplate;

use App\Services\AiTestGenerator;

class TestDefinitionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = TestDefinition::whereHas('website', function ($q) use ($request) {
            $q->where('user_id', $request->user()->id);
        })
        ->with(['website', 'testCases.testRuns' => function ($query) {
            $query->latest()->limit(1);
        }]);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('website', function ($websiteQuery) use ($search) {
                      $websiteQuery->where('url', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by website
        if ($request->filled('website_id')) {
            $query->where('website_id', $request->get('website_id'));
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        // Filter by last run result
        if ($request->filled('last_result')) {
            $result = $request->get('last_result');
            $query->whereHas('testCases', function ($q) use ($result) {
                $q->whereHas('testRuns', function ($runQuery) use ($result) {
                    $runQuery->where('result', $result)
                             ->whereRaw('id IN (SELECT MAX(id) FROM test_runs WHERE test_case_id = test_cases.id GROUP BY test_case_id)');
                });
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        // Validate sort_by to prevent SQL injection
        $allowedSorts = ['created_at', 'updated_at', 'description'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }
        
        // Validate sort_order
        $sortOrder = strtolower($sortOrder) === 'asc' ? 'asc' : 'desc';

        $query->orderBy($sortBy, $sortOrder);

        $testDefinitions = $query->paginate(15)->withQueryString();

        // Get websites for filter dropdown
        $websites = $request->user()->websites()->orderBy('url')->get();

        return view('test_definitions.index', compact('testDefinitions', 'websites'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $websiteId = $request->query('website_id');
        $websites = $request->user()->websites()->get();
        
        if ($websiteId) {
            $website = Website::findOrFail($websiteId);
            if ($website->user_id !== $request->user()->id) {
                abort(403);
            }
        } else {
            $website = null;
            if ($websites->isEmpty()) {
                return redirect()->route('websites.index')->with('error', 'Please add a website first before creating a test definition.');
            }
        }

        $templates = TestDefinitionTemplate::where(function($query) use ($request) {
            $query->where('is_system', true)
                  ->orWhere('user_id', $request->user()->id);
        })->latest()->get();

        return view('test_definitions.create', compact('website', 'websites', 'templates'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, AiTestGenerator $generator)
    {
        $validated = $request->validate([
            'website_id' => 'required|exists:websites,id',
            'description' => 'required|string',
            'generated_steps' => 'required|string',
            'generated_metadata' => 'nullable|string',
            'execute_immediately' => 'sometimes|boolean',
        ]);

        $website = Website::findOrFail($validated['website_id']);

        if ($website->user_id !== $request->user()->id) {
            abort(403);
        }

        $definition = $website->testDefinitions()->create([
            'description' => $validated['description'],
        ]);

        // Use pre-generated steps from preview
        try {
            $steps = json_decode($validated['generated_steps'], true);
            
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($steps) || empty($steps)) {
                throw new \Exception('Invalid generated steps. Please regenerate the steps.');
            }

            // Parse metadata if provided
            $metadata = [];
            if (!empty($validated['generated_metadata'])) {
                $metadata = json_decode($validated['generated_metadata'], true);
            }

            // Steps were generated via Gemini in the preview
            $definition->testCases()->create([
                'steps' => $steps,
                'status' => 'generated',
                'expected_result' => 'Operation successful',
                'generation_source' => $metadata['generation_source'] ?? 'gemini',
                'gemini_model' => $metadata['gemini_model'] ?? null,
            ]);

            $message = 'Test definition saved and test case generated.';
        } catch (\Exception $e) {
            // Delete the definition if generation failed
            $definition->delete();
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['description' => $e->getMessage()]);
        }
        
        // Execute immediately if requested
        if ($request->has('execute_immediately') && $request->boolean('execute_immediately')) {
            $executor = app(\App\Services\TestExecutionService::class);
            $hasFailure = false;
            
            foreach ($definition->testCases as $testCase) {
                $testRun = $executor->run($testCase);
                $testCase->update(['status' => 'completed']);
                
                if ($testRun->result === 'fail') {
                    $hasFailure = true;
                }
            }
            
            // Update website status based on test results
            $website->updateStatusFromTestResult($hasFailure ? 'fail' : 'pass');
            
            $message .= ' Test executed successfully.';
        }

        return redirect()->route('test-definitions.index')->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(TestDefinition $testDefinition)
    {
        if ($testDefinition->website->user_id !== request()->user()->id) {
            abort(403);
        }

        $testDefinition->load(['website', 'testCases.testRuns' => function ($query) {
            $query->latest()->limit(10);
        }]);

        return view('test_definitions.show', compact('testDefinition'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TestDefinition $testDefinition)
    {
        if ($testDefinition->website->user_id !== request()->user()->id) {
            abort(403);
        }

        $testDefinition->load('website');
        $websites = request()->user()->websites()->get();

        return view('test_definitions.edit', compact('testDefinition', 'websites'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TestDefinition $testDefinition, AiTestGenerator $generator)
    {
        if ($testDefinition->website->user_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'website_id' => 'required|exists:websites,id',
            'description' => 'required|string',
            'generated_steps' => 'nullable|string',
            'generated_metadata' => 'nullable|string',
        ]);

        $website = Website::findOrFail($validated['website_id']);

        if ($website->user_id !== $request->user()->id) {
            abort(403);
        }

        $testDefinition->update([
            'website_id' => $validated['website_id'],
            'description' => $validated['description'],
        ]);

        // Update steps if new steps were generated
        if (!empty($validated['generated_steps'])) {
            try {
                $steps = json_decode($validated['generated_steps'], true);
                
                if (json_last_error() !== JSON_ERROR_NONE || !is_array($steps) || empty($steps)) {
                    throw new \Exception('Invalid generated steps. Please regenerate the steps.');
                }

                // Parse metadata if provided
                $metadata = [];
                if (!empty($validated['generated_metadata'])) {
                    $metadata = json_decode($validated['generated_metadata'], true);
                }

                // Update or create test case
                $testCase = $testDefinition->testCases()->first();
                if ($testCase) {
                    $testCase->update([
                        'steps' => $steps,
                        'status' => 'generated',
                        'generation_source' => $metadata['generation_source'] ?? 'gemini',
                        'gemini_model' => $metadata['gemini_model'] ?? null,
                    ]);
                } else {
                    $testDefinition->testCases()->create([
                        'steps' => $steps,
                        'status' => 'generated',
                        'expected_result' => 'Operation successful',
                        'generation_source' => $metadata['generation_source'] ?? 'gemini',
                        'gemini_model' => $metadata['gemini_model'] ?? null,
                    ]);
                }
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['description' => $e->getMessage()]);
            }
        }

        return redirect()->route('test-definitions.index')->with('success', 'Test definition updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TestDefinition $testDefinition)
    {
        if ($testDefinition->website->user_id !== request()->user()->id) {
            abort(403);
        }

        $testDefinition->delete();

        return redirect()->route('test-definitions.index')->with('success', 'Test definition deleted successfully.');
    }

    /**
     * Run the test execution.
     */
    public function run(Request $request, TestDefinition $testDefinition, \App\Services\TestExecutionService $executor)
    {
        if ($testDefinition->website->user_id !== $request->user()->id) {
            abort(403);
        }

        // Check if async execution is requested
        if ($request->has('async') && $request->boolean('async')) {
            return $this->runAsync($request, $testDefinition, $executor);
        }

        $website = $testDefinition->website;
        $hasFailure = false;

        // Run all associated test cases (for now just one usually)
        foreach ($testDefinition->testCases as $testCase) {
             $testRun = $executor->run($testCase);
             $testCase->update(['status' => 'completed']);
             
             // Track if any test failed
             if ($testRun->result === 'fail') {
                 $hasFailure = true;
             }
        }

        // Update website status based on test results
        // If any test failed, mark as error; otherwise mark as pass
        $website->updateStatusFromTestResult($hasFailure ? 'fail' : 'pass');

        return redirect()->back()->with('success', 'Test execution completed.');
    }

    /**
     * Start async test execution and redirect to execution page
     */
    public function runAsync(Request $request, TestDefinition $testDefinition, \App\Services\TestExecutionService $executor)
    {
        if ($testDefinition->website->user_id !== $request->user()->id) {
            abort(403);
        }

        $testCases = $testDefinition->testCases;
        if ($testCases->isEmpty()) {
            return redirect()->back()->with('error', 'No test cases found for this definition.');
        }

        // Create test runs for all test cases
        $testRuns = [];
        foreach ($testCases as $testCase) {
            $testRun = $testCase->testRuns()->create([
                'status' => 'running',
                'current_step' => 0,
                'total_steps' => count($testCase->steps ?? []),
                'result' => null,
                'logs' => [],
                'executed_at' => now(),
            ]);
            
            $testCase->update(['status' => 'running']);
            
            // Execute first step immediately for each test case
            $executor->executeStep($testRun);
            
            $testRuns[] = $testRun;
        }

        // Use the first test run for the route (for backward compatibility)
        // But the view will show all test cases
        return redirect()->route('test-definitions.execute', [
            'testDefinition' => $testDefinition,
            'testRun' => $testRuns[0]
        ]);
    }

    /**
     * Show test execution interface
     */
    public function execute(Request $request, TestDefinition $testDefinition, \App\Models\TestRun $testRun)
    {
        if ($testDefinition->website->user_id !== $request->user()->id) {
            abort(403);
        }

        // Verify test run belongs to test definition
        if ($testRun->testCase->test_definition_id !== $testDefinition->id) {
            abort(404);
        }

        // Load all test cases with their latest test runs
        $testDefinition->load(['website', 'testCases' => function ($query) {
            $query->with(['testRuns' => function ($q) {
                $q->latest()->limit(1);
            }]);
        }]);

        return view('test_definitions.execute', compact('testDefinition', 'testRun'));
    }

    /**
     * Get test execution progress (API endpoint for polling)
     */
    public function progress(Request $request, TestDefinition $testDefinition, \App\Models\TestRun $testRun)
    {
        if ($testDefinition->website->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Verify test run belongs to test definition
        if ($testRun->testCase->test_definition_id !== $testDefinition->id) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $executor = app(\App\Services\TestExecutionService::class);
        
        // Get all test cases for this definition
        $testCases = $testDefinition->testCases()->with(['testRuns' => function ($query) {
            $query->latest()->limit(1);
        }])->get();
        
        $allTestRuns = [];
        $allCompleted = true;
        $hasFailure = false;
        
        // Process all test cases
        foreach ($testCases as $testCase) {
            $latestTestRun = $testCase->testRuns->first();
            
            if (!$latestTestRun) {
                // Create test run if it doesn't exist
                $latestTestRun = $testCase->testRuns()->create([
                    'status' => 'running',
                    'current_step' => 0,
                    'total_steps' => count($testCase->steps ?? []),
                    'result' => null,
                    'logs' => [],
                    'executed_at' => now(),
                ]);
                $testCase->update(['status' => 'running']);
            }
            
            // Continue execution if still running
            if ($latestTestRun->status === 'running' || $latestTestRun->status === 'pending') {
                $latestTestRun = $executor->executeStep($latestTestRun->fresh());
                $allCompleted = false;
            }
            
            // Track failures
            if ($latestTestRun->result === 'fail') {
                $hasFailure = true;
            }
            
            $allTestRuns[] = [
                'test_case_id' => $testCase->id,
                'test_run_id' => $latestTestRun->id,
                'status' => $latestTestRun->status,
                'current_step' => $latestTestRun->current_step ?? 0,
                'total_steps' => $latestTestRun->total_steps ?? 0,
                'result' => $latestTestRun->result,
                'logs' => $latestTestRun->logs ?? [],
                'progress_percent' => $latestTestRun->total_steps > 0 
                    ? round((($latestTestRun->current_step ?? 0) / $latestTestRun->total_steps) * 100) 
                    : 0,
            ];
        }
        
        // Update website status if all completed
        if ($allCompleted) {
            foreach ($testCases as $testCase) {
                $testCase->update(['status' => 'completed']);
            }
            $website = $testDefinition->website;
            $website->updateStatusFromTestResult($hasFailure ? 'fail' : 'pass');
        }
        
        // Calculate overall progress
        $totalSteps = array_sum(array_column($allTestRuns, 'total_steps'));
        $completedSteps = array_sum(array_column($allTestRuns, 'current_step'));
        $overallProgress = $totalSteps > 0 ? round(($completedSteps / $totalSteps) * 100) : 0;
        
        // Determine overall status
        $overallStatus = $allCompleted ? 'completed' : 'running';
        $overallResult = $allCompleted ? ($hasFailure ? 'fail' : 'pass') : null;
        
        return response()->json([
            'overall_status' => $overallStatus,
            'overall_result' => $overallResult,
            'overall_progress' => $overallProgress,
            'test_runs' => $allTestRuns,
            'total_test_cases' => count($testCases),
            'completed_test_cases' => count(array_filter($allTestRuns, fn($tr) => $tr['status'] === 'completed')),
        ]);
    }

    /**
     * Preview test steps generated from description (for AJAX preview)
     */
    public function preview(Request $request, AiTestGenerator $generator)
    {
        $request->validate([
            'description' => 'required|string|min:10',
            'website_id' => 'nullable|exists:websites,id',
        ]);

        $description = $request->input('description');
        $websiteId = $request->input('website_id');
        $websiteUrl = null;

        // Get website URL if provided
        if ($websiteId) {
            $website = Website::find($websiteId);
            if ($website && $website->user_id === $request->user()->id) {
                $websiteUrl = $website->url;
            }
        }

        try {
            $generationResult = $generator->generate($description, $websiteUrl);
            
            return response()->json([
                'success' => true,
                'steps' => $generationResult['steps'],
                'metadata' => $generationResult['metadata'],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
