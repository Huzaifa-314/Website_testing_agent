<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestGeminiApiKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gemini:test-key';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test if the Gemini API key in .env is working';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $apiKey = env('GEMINI_API_KEY');

        if (empty($apiKey)) {
            $this->error('❌ GEMINI_API_KEY not found in .env file');
            return Command::FAILURE;
        }

        $this->info('🔑 Found GEMINI_API_KEY in .env');
        $this->info('🔍 Testing API key...');
        $this->newLine();

        try {
            // First, try to list available models to verify API key works
            $this->line('Step 1: Checking API key by listing available models...');
            $listModelsUrl = "https://generativelanguage.googleapis.com/v1beta/models?key={$apiKey}";
            
            $listResponse = Http::timeout(10)->get($listModelsUrl);
            
            if (!$listResponse->successful()) {
                $statusCode = $listResponse->status();
                $errorBody = $listResponse->json();
                
                $this->error("❌ API Key test FAILED (HTTP {$statusCode})");
                
                if (isset($errorBody['error'])) {
                    $errorMessage = $errorBody['error']['message'] ?? 'Unknown error';
                    $errorCode = $errorBody['error']['code'] ?? 'Unknown';
                    $this->error("Error Code: {$errorCode}");
                    $this->error("Error Message: {$errorMessage}");
                    
                    if ($statusCode === 400) {
                        $this->warn('💡 This might indicate an invalid API key or API endpoint issue');
                    } elseif ($statusCode === 403) {
                        $this->warn('💡 This might indicate the API key is invalid or lacks required permissions');
                    } elseif ($statusCode === 401) {
                        $this->warn('💡 This indicates the API key is invalid or expired');
                    }
                } else {
                    $this->line('Response: ' . $listResponse->body());
                }
                
                return Command::FAILURE;
            }
            
            $this->info('✅ API Key is VALID! Successfully connected to Gemini API.');
            $this->newLine();
            
            // Try to get a list of models
            $modelsData = $listResponse->json();
            if (isset($modelsData['models']) && count($modelsData['models']) > 0) {
                $this->info('📋 Available models:');
                foreach ($modelsData['models'] as $model) {
                    $name = $model['name'] ?? 'Unknown';
                    $displayName = $model['displayName'] ?? $name;
                    $this->line("   - {$displayName} ({$name})");
                }
                $this->newLine();
            }
            
            // Now test with a simple generateContent request
            $this->line('Step 2: Testing content generation...');
            
            // Use a model that supports generateContent - try gemini-flash-latest first
            $modelName = 'gemini-flash-latest';
            $generateUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent?key={$apiKey}";
            
            $generateResponse = Http::timeout(10)->post($generateUrl, [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => 'Say "Hello" in one word.'
                            ]
                        ]
                    ]
                ]
            ]);
            
            if ($generateResponse->successful()) {
                $data = $generateResponse->json();
                
                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    $this->info("✅ Content generation successful with model: {$modelName}");
                    $this->info('📝 Response: ' . trim($data['candidates'][0]['content']['parts'][0]['text']));
                } else {
                    $this->warn('⚠️  Response received but format unexpected');
                    $this->line('Response: ' . json_encode($data, JSON_PRETTY_PRINT));
                }
            } else {
                $statusCode = $generateResponse->status();
                $errorBody = $generateResponse->json();
                
                if (isset($errorBody['error'])) {
                    $errorMessage = $errorBody['error']['message'] ?? 'Unknown error';
                    $this->warn("⚠️  Content generation test failed (HTTP {$statusCode}): {$errorMessage}");
                } else {
                    $this->warn("⚠️  Content generation test failed (HTTP {$statusCode})");
                }
                $this->info('💡 But the API key is valid since we successfully listed models!');
            }
            
            $this->newLine();
            $this->info('✅ Summary: Gemini API key is WORKING!');
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error('❌ Error testing API key: ' . $e->getMessage());
            $this->error('Exception: ' . get_class($e));
            if ($this->getOutput()->isVerbose()) {
                $this->error('Stack trace: ' . $e->getTraceAsString());
            }
            return Command::FAILURE;
        }
    }
}
