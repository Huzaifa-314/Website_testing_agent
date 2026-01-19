<?php

namespace Database\Seeders;

use App\Models\TestDefinition;
use App\Models\TestCase;
use Illuminate\Database\Seeder;

class TestCaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testDefinitions = TestDefinition::all();

        foreach ($testDefinitions as $testDefinition) {
            $description = strtolower($testDefinition->description);
            if (str_contains($description, 'login') || str_contains($description, 'auth')) {
                // Login test case
                TestCase::updateOrCreate(
                    [
                        'test_definition_id' => $testDefinition->id,
                        'expected_result' => 'User should be redirected to dashboard after successful login',
                    ],
                    [
                        'test_definition_id' => $testDefinition->id,
                        'steps' => [
                            ['action' => 'visit', 'url' => '/login'],
                            ['action' => 'type', 'selector' => 'input[name="email"]', 'value' => 'test@example.com'],
                            ['action' => 'type', 'selector' => 'input[name="password"]', 'value' => 'password'],
                            ['action' => 'click', 'selector' => 'button[type="submit"]'],
                            ['action' => 'assert_url', 'value' => '/dashboard'],
                        ],
                        'expected_result' => 'User should be redirected to dashboard after successful login',
                        'status' => 'pending',
                    ]
                );
            } elseif (str_contains($description, 'homepage') || str_contains($description, 'load')) {
                // Homepage load test case
                TestCase::updateOrCreate(
                    [
                        'test_definition_id' => $testDefinition->id,
                        'expected_result' => 'Homepage should load with status code 200',
                    ],
                    [
                        'test_definition_id' => $testDefinition->id,
                        'steps' => [
                            ['action' => 'visit', 'url' => '/'],
                            ['action' => 'assert_status', 'value' => 200],
                        ],
                        'expected_result' => 'Homepage should load with status code 200',
                        'status' => 'pending',
                    ]
                );
            } elseif (str_contains($description, 'form') || str_contains($description, 'contact')) {
                // Contact form test case
                TestCase::updateOrCreate(
                    [
                        'test_definition_id' => $testDefinition->id,
                        'expected_result' => 'Success message should appear after form submission',
                    ],
                    [
                        'test_definition_id' => $testDefinition->id,
                        'steps' => [
                            ['action' => 'visit', 'url' => '/contact'],
                            ['action' => 'type', 'selector' => 'input[name="name"]', 'value' => 'John Doe'],
                            ['action' => 'type', 'selector' => 'textarea[name="message"]', 'value' => 'Hello, this is a test message'],
                            ['action' => 'click', 'selector' => 'button[type="submit"]'],
                            ['action' => 'assert_text', 'value' => 'Thank you'],
                        ],
                        'expected_result' => 'Success message should appear after form submission',
                        'status' => 'pending',
                    ]
                );
            }
        }
    }
}

