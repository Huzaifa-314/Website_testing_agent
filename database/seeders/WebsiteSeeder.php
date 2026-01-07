<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Website;
use Illuminate\Database\Seeder;

class WebsiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testUser = User::where('email', 'test@example.com')->first();
        $adminUser = User::where('email', 'admin@klydos.com')->first();
        $demoUser = User::where('email', 'demo@example.com')->first();

        // Websites for test user
        if ($testUser) {
            Website::updateOrCreate(
                ['url' => 'https://example.com', 'user_id' => $testUser->id],
                [
                    'user_id' => $testUser->id,
                    'url' => 'https://example.com',
                    'status' => 'active',
                ]
            );

            Website::updateOrCreate(
                ['url' => 'https://test-site.com', 'user_id' => $testUser->id],
                [
                    'user_id' => $testUser->id,
                    'url' => 'https://test-site.com',
                    'status' => 'pending',
                ]
            );
        }

        // Websites for admin user
        if ($adminUser) {
            Website::updateOrCreate(
                ['url' => 'https://laravel.com', 'user_id' => $adminUser->id],
                [
                    'user_id' => $adminUser->id,
                    'url' => 'https://laravel.com',
                    'status' => 'active',
                ]
            );

            Website::updateOrCreate(
                ['url' => 'https://github.com', 'user_id' => $adminUser->id],
                [
                    'user_id' => $adminUser->id,
                    'url' => 'https://github.com',
                    'status' => 'active',
                ]
            );
        }

        // Websites for demo user
        if ($demoUser) {
            Website::updateOrCreate(
                ['url' => 'https://demo-site.com', 'user_id' => $demoUser->id],
                [
                    'user_id' => $demoUser->id,
                    'url' => 'https://demo-site.com',
                    'status' => 'pending',
                ]
            );
        }
    }
}

