<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\User;
use App\Models\Website;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $websites = Website::all();

        foreach ($users as $user) {
            $userWebsites = $websites->where('user_id', $user->id);

            foreach ($userWebsites as $website) {
                // Create a summary report
                Report::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'website_id' => $website->id,
                        'generated_at' => now()->subDays(1),
                    ],
                    [
                        'user_id' => $user->id,
                        'website_id' => $website->id,
                        'summary' => "Test execution summary for {$website->url}. Total tests: 5, Passed: 4, Failed: 1. Average execution time: 2.3s. Last test run completed successfully.",
                        'generated_at' => now()->subDays(1),
                    ]
                );

                // Create an older report
                Report::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'website_id' => $website->id,
                        'generated_at' => now()->subDays(7),
                    ],
                    [
                        'user_id' => $user->id,
                        'website_id' => $website->id,
                        'summary' => "Weekly test report for {$website->url}. All critical tests passed. Performance metrics are within acceptable range.",
                        'generated_at' => now()->subDays(7),
                    ]
                );
            }
        }
    }
}

