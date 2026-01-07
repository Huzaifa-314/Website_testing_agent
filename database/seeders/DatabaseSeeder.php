<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Seed users first (no dependencies)
            UserSeeder::class,
            
            // Seed settings (no dependencies)
            SettingSeeder::class,
            
            // Seed websites (depends on users)
            WebsiteSeeder::class,
            
            // Seed test definition templates (depends on users, but user_id can be null)
            TestDefinitionTemplateSeeder::class,
            
            // Seed test definitions (depends on websites)
            TestDefinitionSeeder::class,
            
            // Seed test cases (depends on test definitions)
            TestCaseSeeder::class,
            
            // Seed test runs (depends on test cases)
            TestRunSeeder::class,
            
            // Seed reports (depends on users and websites)
            ReportSeeder::class,
            
            // Seed activity logs (depends on users, but user_id can be null)
            ActivityLogSeeder::class,
        ]);
    }
}
