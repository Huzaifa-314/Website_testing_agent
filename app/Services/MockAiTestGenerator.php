<?php

namespace App\Services;

class MockAiTestGenerator
{
    /**
     * Generate test steps based on description and scope.
     *
     * @param string $description
     * @param string $scope
     * @return array
     */
    public function generate(string $description, string $scope): array
    {
        $steps = [];
        $description = strtolower($description);

        if ($scope === 'auth' || str_contains($description, 'login')) {
            $steps[] = [
                'action' => 'visit',
                'url' => '/login',
            ];
            $steps[] = [
                'action' => 'type',
                'selector' => 'input[name="email"]',
                'value' => 'test@example.com',
            ];
            $steps[] = [
                'action' => 'type',
                'selector' => 'input[name="password"]',
                'value' => 'password',
            ];
            $steps[] = [
                'action' => 'click',
                'selector' => 'button[type="submit"]',
            ];
            $steps[] = [
                'action' => 'assert_url',
                'value' => '/dashboard',
            ];
        } elseif ($scope === 'form' || str_contains($description, 'form')) {
            $steps[] = [
                'action' => 'visit',
                'url' => '/contact',
            ];
            $steps[] = [
                'action' => 'type',
                'selector' => 'input[name="name"]',
                'value' => 'John Doe',
            ];
            $steps[] = [
                'action' => 'type',
                'selector' => 'textarea[name="message"]',
                'value' => 'Hello World',
            ];
            $steps[] = [
                'action' => 'click',
                'selector' => 'button[type="submit"]',
            ];
            $steps[] = [
                'action' => 'assert_text',
                'value' => 'Thank you',
            ];
        } else {
            // Default generic step
            $steps[] = [
                'action' => 'visit',
                'url' => '/',
            ];
            $steps[] = [
                'action' => 'assert_status',
                'value' => 200,
            ];
        }

        return $steps;
    }
}
