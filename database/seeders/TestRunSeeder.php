<?php

namespace Database\Seeders;

use App\Models\TestCase;
use App\Models\TestRun;
use Illuminate\Database\Seeder;

class TestRunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testCases = TestCase::all();

        foreach ($testCases as $testCase) {
            $steps = $testCase->steps ?? [];
            $totalSteps = count($steps);

            // Create a completed test run
            TestRun::updateOrCreate(
                [
                    'test_case_id' => $testCase->id,
                    'executed_at' => now()->subDays(2),
                ],
                [
                    'test_case_id' => $testCase->id,
                    'status' => 'completed',
                    'current_step' => $totalSteps,
                    'total_steps' => $totalSteps,
                    'result' => 'pass',
                    'logs' => [
                        ['step' => 1, 'action' => 'visit', 'status' => 'success', 'message' => 'Page loaded successfully'],
                        ['step' => 2, 'action' => 'type', 'status' => 'success', 'message' => 'Text entered successfully'],
                        ['step' => 3, 'action' => 'click', 'status' => 'success', 'message' => 'Button clicked successfully'],
                    ],
                    'executed_at' => now()->subDays(2),
                ]
            );

            // Create a recent test run
            TestRun::updateOrCreate(
                [
                    'test_case_id' => $testCase->id,
                    'executed_at' => now()->subHours(5),
                ],
                [
                    'test_case_id' => $testCase->id,
                    'status' => 'completed',
                    'current_step' => $totalSteps,
                    'total_steps' => $totalSteps,
                    'result' => 'pass',
                    'logs' => [
                        ['step' => 1, 'action' => 'visit', 'status' => 'success', 'message' => 'Page loaded successfully'],
                        ['step' => 2, 'action' => 'assert_status', 'status' => 'success', 'message' => 'Status code 200 verified'],
                    ],
                    'executed_at' => now()->subHours(5),
                ]
            );

            // Create a failed test run for some test cases
            if ($testCase->id % 3 === 0) {
                TestRun::updateOrCreate(
                    [
                        'test_case_id' => $testCase->id,
                        'executed_at' => now()->subDay(),
                    ],
                    [
                        'test_case_id' => $testCase->id,
                        'status' => 'completed',
                        'current_step' => 2,
                        'total_steps' => $totalSteps,
                        'result' => 'fail',
                        'logs' => [
                            ['step' => 1, 'action' => 'visit', 'status' => 'success', 'message' => 'Page loaded successfully'],
                            ['step' => 2, 'action' => 'type', 'status' => 'error', 'message' => 'Element not found'],
                        ],
                        'executed_at' => now()->subDay(),
                    ]
                );
            }
        }
    }
}

