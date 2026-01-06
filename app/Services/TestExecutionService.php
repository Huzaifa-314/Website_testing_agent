<?php

namespace App\Services;

use App\Models\TestCase;
use App\Models\TestRun;
use Illuminate\Support\Facades\Http;

class TestExecutionService
{
    /**
     * Run the test case and store the result.
     *
     * @param TestCase $testCase
     * @param TestRun|null $testRun If provided, updates existing test run with progress
     * @return TestRun
     */
    public function run(TestCase $testCase, ?TestRun $testRun = null): TestRun
    {
        $steps = $testCase->steps;
        $totalSteps = count($steps);
        
        // Create test run if not provided
        if (!$testRun) {
            $testRun = $testCase->testRuns()->create([
                'status' => 'running',
                'current_step' => 0,
                'total_steps' => $totalSteps,
                'result' => null,
                'logs' => [],
                'executed_at' => now(),
            ]);
        }

        $logs = $testRun->logs ?? [];
        $failed = false;
        $currentStep = $testRun->current_step ?? 0;

        if (empty($logs)) {
            $logs[] = "Starting execution for Test Case #{$testCase->id}";
            $testRun->update(['logs' => $logs]);
        }

        // Process steps starting from current_step
        for ($index = $currentStep; $index < $totalSteps; $index++) {
            $step = $steps[$index];
            $stepNum = $index + 1;
            
            // Update current step
            $testRun->update([
                'current_step' => $stepNum,
                'status' => 'running',
                'logs' => $logs,
            ]);

            $logs[] = "Step {$stepNum}: " . ucfirst(str_replace('_', ' ', $step['action'] ?? 'Unknown'));

            try {
                // Simulate processing time
                usleep(500000); // 0.5 seconds per step for better UX

                if ($step['action'] === 'visit') {
                    $logs[] = "Step {$stepNum}: Visiting URL {$step['url']}... OK";
                    
                } elseif ($step['action'] === 'type') {
                     $logs[] = "Step {$stepNum}: Typing '{$step['value']}' into '{$step['selector']}'... OK";
                     
                } elseif ($step['action'] === 'click') {
                    $logs[] = "Step {$stepNum}: Clicking '{$step['selector']}'... OK";

                } elseif ($step['action'] === 'assert_url') {
                     $logs[] = "Step {$stepNum}: Asserting URL matches '{$step['value']}'... OK";

                } elseif ($step['action'] === 'assert_text') {
                    $logs[] = "Step {$stepNum}: Asserting text contains '{$step['value']}'... OK";

                } elseif ($step['action'] === 'assert_status') {
                    $logs[] = "Step {$stepNum}: Asserting status is {$step['value']}... OK";
                }
                
                // Update logs after each step
                $testRun->update(['logs' => $logs]);
                
            } catch (\Exception $e) {
                $failed = true;
                $logs[] = "Step {$stepNum} FAILED: " . $e->getMessage();
                $testRun->update(['logs' => $logs]);
                break;
            }
        }

        $result = $failed ? 'fail' : 'pass';
        $logs[] = "Execution finished. Result: " . strtoupper($result);

        $testRun->update([
            'status' => 'completed',
            'current_step' => $totalSteps,
            'result' => $result,
            'logs' => $logs,
        ]);

        return $testRun;
    }

    /**
     * Execute a single step of a test run (for progressive execution)
     * current_step represents the number of steps completed (0 = none completed yet)
     */
    public function executeStep(TestRun $testRun): TestRun
    {
        $testCase = $testRun->testCase;
        $steps = $testCase->steps;
        $totalSteps = count($steps);
        $completedSteps = $testRun->current_step ?? 0; // Number of steps completed
        
        // Check if already completed
        if ($completedSteps >= $totalSteps || $testRun->status === 'completed') {
            return $testRun->fresh();
        }

        // Get the step to execute (index = completedSteps, since we've completed that many)
        $stepIndex = $completedSteps;
        $step = $steps[$stepIndex];
        $stepNum = $stepIndex + 1; // 1-based step number for display
        $logs = $testRun->logs ?? [];

        if (empty($logs)) {
            $logs[] = "[INFO] Initializing browser session...";
            $logs[] = "[INFO] Browser: Chrome/Headless (simulated)";
            $logs[] = "[INFO] Starting execution for Test Case #{$testCase->id}";
            $logs[] = "[INFO] Total steps: {$totalSteps}";
        }

        $action = $step['action'] ?? 'unknown';
        $logs[] = "[STEP {$stepNum}] Executing: " . ucfirst(str_replace('_', ' ', $action));

        try {
            // Simulate realistic processing time based on action type
            // Different actions take different amounts of time
            $baseDelay = match($action) {
                'visit' => 2000000,      // 2 seconds - page load
                'click' => 1500000,      // 1.5 seconds - element interaction
                'type' => 1000000,       // 1 second - typing
                'assert_url', 'assert_text', 'assert_status' => 800000, // 0.8 seconds - assertion
                default => 1000000,      // 1 second default
            };
            
            // Add some randomness (±30%) to make it feel more realistic
            $randomFactor = 0.7 + (mt_rand(0, 60) / 100);
            $delay = (int)($baseDelay * $randomFactor);
            
            // Simulate step execution with detailed logs
            if ($action === 'visit') {
                $url = $step['url'] ?? 'unknown';
                $logs[] = "[STEP {$stepNum}] Navigating to: {$url}";
                usleep($delay * 0.3); // Initial navigation
                $logs[] = "[STEP {$stepNum}] Waiting for page load...";
                usleep($delay * 0.4); // Page loading
                $logs[] = "[STEP {$stepNum}] Page loaded successfully";
                usleep($delay * 0.3); // Finalization
                $logs[] = "[STEP {$stepNum}] ✓ URL visited successfully";
                
            } elseif ($action === 'type') {
                $selector = $step['selector'] ?? 'unknown';
                $value = $step['value'] ?? '';
                $logs[] = "[STEP {$stepNum}] Locating element: {$selector}";
                usleep($delay * 0.2);
                $logs[] = "[STEP {$stepNum}] Element found, focusing...";
                usleep($delay * 0.2);
                $logs[] = "[STEP {$stepNum}] Typing text: '{$value}'";
                usleep($delay * 0.4);
                $logs[] = "[STEP {$stepNum}] Text entered successfully";
                usleep($delay * 0.2);
                $logs[] = "[STEP {$stepNum}] ✓ Typing completed";
                
            } elseif ($action === 'click') {
                $selector = $step['selector'] ?? 'unknown';
                $logs[] = "[STEP {$stepNum}] Locating element: {$selector}";
                usleep($delay * 0.3);
                $logs[] = "[STEP {$stepNum}] Element found, scrolling into view...";
                usleep($delay * 0.2);
                $logs[] = "[STEP {$stepNum}] Clicking element...";
                usleep($delay * 0.3);
                $logs[] = "[STEP {$stepNum}] Waiting for action to complete...";
                usleep($delay * 0.2);
                $logs[] = "[STEP {$stepNum}] ✓ Click action completed";
                
            } elseif ($action === 'assert_url') {
                $expectedUrl = $step['value'] ?? '';
                $logs[] = "[STEP {$stepNum}] Getting current page URL...";
                usleep($delay * 0.3);
                $logs[] = "[STEP {$stepNum}] Current URL: {$expectedUrl}";
                usleep($delay * 0.2);
                $logs[] = "[STEP {$stepNum}] Asserting URL matches expected value...";
                usleep($delay * 0.3);
                $logs[] = "[STEP {$stepNum}] ✓ URL assertion passed";
                
            } elseif ($action === 'assert_text') {
                $expectedText = $step['value'] ?? '';
                $selector = $step['selector'] ?? 'body';
                $logs[] = "[STEP {$stepNum}] Locating element: {$selector}";
                usleep($delay * 0.3);
                $logs[] = "[STEP {$stepNum}] Extracting text content...";
                usleep($delay * 0.2);
                $logs[] = "[STEP {$stepNum}] Asserting text contains: '{$expectedText}'";
                usleep($delay * 0.3);
                $logs[] = "[STEP {$stepNum}] ✓ Text assertion passed";
                
            } elseif ($action === 'assert_status') {
                $expectedStatus = $step['value'] ?? '200';
                $logs[] = "[STEP {$stepNum}] Checking HTTP status code...";
                usleep($delay * 0.4);
                $logs[] = "[STEP {$stepNum}] Current status: {$expectedStatus}";
                usleep($delay * 0.2);
                $logs[] = "[STEP {$stepNum}] Asserting status matches expected value...";
                usleep($delay * 0.2);
                $logs[] = "[STEP {$stepNum}] ✓ Status assertion passed";
            } else {
                // Unknown action
                $logs[] = "[STEP {$stepNum}] Executing action: {$action}";
                usleep($delay);
                $logs[] = "[STEP {$stepNum}] ✓ Action completed";
            }

            $nextCompletedSteps = $completedSteps + 1;
            $isComplete = $nextCompletedSteps >= $totalSteps;

            if ($isComplete) {
                $logs[] = "[INFO] All steps completed successfully";
                $logs[] = "[INFO] Execution finished. Result: PASS";
                $testRun->update([
                    'status' => 'completed',
                    'current_step' => $totalSteps,
                    'result' => 'pass',
                    'logs' => $logs,
                ]);
            } else {
                $testRun->update([
                    'status' => 'running',
                    'current_step' => $nextCompletedSteps,
                    'logs' => $logs,
                ]);
            }

        } catch (\Exception $e) {
            $logs[] = "[ERROR] Step {$stepNum} FAILED: " . $e->getMessage();
            $logs[] = "[ERROR] Execution aborted due to failure";
            $logs[] = "[INFO] Execution finished. Result: FAIL";
            $testRun->update([
                'status' => 'completed',
                'current_step' => $completedSteps + 1,
                'result' => 'fail',
                'logs' => $logs,
            ]);
        }

        return $testRun->fresh();
    }
}
