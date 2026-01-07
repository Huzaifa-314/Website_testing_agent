<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'app_name',
                'value' => 'Klydos',
                'type' => 'string',
            ],
            [
                'key' => 'app_timezone',
                'value' => 'UTC',
                'type' => 'string',
            ],
            [
                'key' => 'test_timeout',
                'value' => '30',
                'type' => 'integer',
            ],
            [
                'key' => 'max_test_runs_per_hour',
                'value' => '100',
                'type' => 'integer',
            ],
            [
                'key' => 'enable_email_notifications',
                'value' => '1',
                'type' => 'boolean',
            ],
            [
                'key' => 'default_test_scope',
                'value' => 'workflow',
                'type' => 'string',
            ],
            [
                'key' => 'retention_days',
                'value' => '90',
                'type' => 'integer',
            ],
            [
                'key' => 'allowed_domains',
                'value' => json_encode(['example.com', 'test.com', 'demo.com']),
                'type' => 'json',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}

