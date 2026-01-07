<?php

namespace Database\Seeders;

use App\Models\Website;
use App\Models\TestDefinition;
use Illuminate\Database\Seeder;

class TestDefinitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $websites = Website::all();

        foreach ($websites as $website) {
            // Login test definition
            TestDefinition::updateOrCreate(
                [
                    'website_id' => $website->id,
                    'description' => 'Test user login functionality with valid credentials',
                ],
                [
                    'website_id' => $website->id,
                    'description' => 'Test user login functionality with valid credentials',
                    'test_scope' => 'auth',
                ]
            );

            // Homepage load test definition
            TestDefinition::updateOrCreate(
                [
                    'website_id' => $website->id,
                    'description' => 'Verify homepage loads correctly with status code 200',
                ],
                [
                    'website_id' => $website->id,
                    'description' => 'Verify homepage loads correctly with status code 200',
                    'test_scope' => 'workflow',
                ]
            );

            // Contact form test definition
            TestDefinition::updateOrCreate(
                [
                    'website_id' => $website->id,
                    'description' => 'Test contact form submission and success message',
                ],
                [
                    'website_id' => $website->id,
                    'description' => 'Test contact form submission and success message',
                    'test_scope' => 'form',
                ]
            );
        }
    }
}

