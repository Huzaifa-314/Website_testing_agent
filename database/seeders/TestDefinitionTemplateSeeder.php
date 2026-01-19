<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TestDefinitionTemplate;

class TestDefinitionTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Login Authentication Test',
                'description' => 'Test user login functionality with valid credentials',
                'example_description' => 'Go to login page, enter valid email and password, click login button, and verify redirect to dashboard',
                'example_steps' => [
                    ['action' => 'visit', 'url' => '/login'],
                    ['action' => 'type', 'selector' => 'input[name="email"]', 'value' => 'test@example.com'],
                    ['action' => 'type', 'selector' => 'input[name="password"]', 'value' => 'password'],
                    ['action' => 'click', 'selector' => 'button[type="submit"]'],
                    ['action' => 'assert_url', 'value' => '/dashboard'],
                ],
                'is_system' => true,
            ],
            [
                'name' => 'Contact Form Submission',
                'description' => 'Test contact form submission and success message',
                'example_description' => 'Navigate to contact page, fill in name and message fields, submit form, and verify success message appears',
                'example_steps' => [
                    ['action' => 'visit', 'url' => '/contact'],
                    ['action' => 'type', 'selector' => 'input[name="name"]', 'value' => 'John Doe'],
                    ['action' => 'type', 'selector' => 'textarea[name="message"]', 'value' => 'Hello, this is a test message'],
                    ['action' => 'click', 'selector' => 'button[type="submit"]'],
                    ['action' => 'assert_text', 'value' => 'Thank you'],
                ],
                'is_system' => true,
            ],
            [
                'name' => 'Homepage Load Test',
                'description' => 'Verify homepage loads correctly',
                'example_description' => 'Visit homepage and verify page loads with status code 200',
                'example_steps' => [
                    ['action' => 'visit', 'url' => '/'],
                    ['action' => 'assert_status', 'value' => 200],
                ],
                'is_system' => true,
            ],
            [
                'name' => 'User Registration Flow',
                'description' => 'Test complete user registration process',
                'example_description' => 'Go to registration page, fill in all required fields, submit form, and verify account creation',
                'example_steps' => [
                    ['action' => 'visit', 'url' => '/register'],
                    ['action' => 'type', 'selector' => 'input[name="name"]', 'value' => 'Test User'],
                    ['action' => 'type', 'selector' => 'input[name="email"]', 'value' => 'newuser@example.com'],
                    ['action' => 'type', 'selector' => 'input[name="password"]', 'value' => 'password123'],
                    ['action' => 'type', 'selector' => 'input[name="password_confirmation"]', 'value' => 'password123'],
                    ['action' => 'click', 'selector' => 'button[type="submit"]'],
                    ['action' => 'assert_text', 'value' => 'Welcome'],
                ],
                'is_system' => true,
            ],
            [
                'name' => 'API Endpoint Test',
                'description' => 'Test API endpoint response',
                'example_description' => 'Make GET request to API endpoint and verify response status and data structure',
                'example_steps' => [
                    ['action' => 'visit', 'url' => '/api/users'],
                    ['action' => 'assert_status', 'value' => 200],
                    ['action' => 'assert_json', 'value' => 'data'],
                ],
                'is_system' => true,
            ],
        ];

        foreach ($templates as $template) {
            TestDefinitionTemplate::updateOrCreate(
                ['name' => $template['name'], 'is_system' => true],
                $template
            );
        }
    }
}
