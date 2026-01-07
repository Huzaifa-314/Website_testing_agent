<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class ActivityLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            // Login activity
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'login',
                'description' => "User {$user->name} logged in successfully",
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'metadata' => ['login_method' => 'email'],
                'created_at' => now()->subDays(3),
            ]);

            // Website creation activity
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'create_website',
                'description' => "User {$user->name} created a new website",
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'metadata' => ['website_count' => $user->websites()->count()],
                'created_at' => now()->subDays(2),
            ]);

            // Test run activity
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'run_test',
                'description' => "User {$user->name} executed a test run",
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'metadata' => ['test_result' => 'pass'],
                'created_at' => now()->subDay(),
            ]);

            // Report generation activity
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'generate_report',
                'description' => "User {$user->name} generated a test report",
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'metadata' => ['report_type' => 'summary'],
                'created_at' => now()->subHours(12),
            ]);
        }

        // System activity (no user)
        ActivityLog::create([
            'user_id' => null,
            'action' => 'system',
            'description' => 'System maintenance completed',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'System',
            'metadata' => ['maintenance_type' => 'scheduled'],
            'created_at' => now()->subDays(5),
        ]);
    }
}

