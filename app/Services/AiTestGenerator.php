<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiTestGenerator
{
    /**
     * Generate test steps using Gemini AI based on description.
     *
     * @param string $description Natural language description of the test
     * @param string|null $websiteUrl Optional website URL for context
     * @return array Array with 'steps' and 'metadata' keys
     * @throws \Exception When API key is missing or API call fails
     */
    public function generate(string $description, ?string $websiteUrl = null): array
    {
        $apiKey = env('GEMINI_API_KEY');
        
        if (empty($apiKey)) {
            Log::error('GEMINI_API_KEY not found in environment');
            throw new \Exception('Gemini API key is not configured. Please set GEMINI_API_KEY in your .env file.');
        }

        try {
            // Try models in order of preference (cost-effective, fast models first)
            $models = ['gemini-2.0-flash-exp', 'gemini-flash-latest', 'gemini-1.5-flash'];
            $lastError = null;
            
            $prompt = $this->buildPrompt($description, $websiteUrl);
            
            foreach ($models as $modelName) {
                try {
                    $generateUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent?key={$apiKey}";

                    $response = Http::timeout(30)->post($generateUrl, [
                        'contents' => [
                            [
                                'parts' => [
                                    [
                                        'text' => $prompt
                                    ]
                                ]
                            ]
                        ],
                        'generationConfig' => [
                            'temperature' => 0.3, // Lower temperature for more consistent, structured output
                            'topP' => 0.95,
                            'topK' => 40,
                            'maxOutputTokens' => 2048,
                        ]
                    ]);

                    if (!$response->successful()) {
                        $lastError = [
                            'model' => $modelName,
                            'status' => $response->status(),
                            'body' => $response->body()
                        ];
                        continue; // Try next model
                    }

                    $data = $response->json();

                    if (!isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                        $lastError = [
                            'model' => $modelName,
                            'error' => 'Unexpected response format',
                            'response' => $data
                        ];
                        continue; // Try next model
                    }

                    $generatedText = trim($data['candidates'][0]['content']['parts'][0]['text']);
                    
                    // Extract JSON from the response (handle cases where AI adds markdown formatting)
                    $jsonMatch = preg_match('/```json\s*([\s\S]*?)\s*```/', $generatedText, $matches);
                    if ($jsonMatch) {
                        $jsonText = $matches[1];
                    } else {
                        // Try to find JSON object directly
                        $jsonMatch = preg_match('/\{[\s\S]*\}/', $generatedText, $matches);
                        $jsonText = $jsonMatch ? $matches[0] : $generatedText;
                    }

                    $decoded = json_decode($jsonText, true);

                    if (json_last_error() !== JSON_ERROR_NONE || !isset($decoded['steps']) || !is_array($decoded['steps'])) {
                        $lastError = [
                            'model' => $modelName,
                            'json_error' => json_last_error_msg(),
                            'response' => $generatedText
                        ];
                        continue; // Try next model
                    }

                    // Success! Return the generated steps with metadata
                    Log::info('Successfully generated test steps using Gemini', ['model' => $modelName]);
                    return [
                        'steps' => $decoded['steps'],
                        'metadata' => [
                            'generation_source' => 'gemini',
                            'gemini_model' => $modelName,
                            'used_ai' => true,
                        ]
                    ];
                } catch (\Exception $e) {
                    $lastError = [
                        'model' => $modelName,
                        'exception' => $e->getMessage()
                    ];
                    continue; // Try next model
                }
            }
            
            // All models failed - throw exception with details
            $errorMessage = 'Failed to generate test steps using Gemini AI. ';
            if ($lastError) {
                if (isset($lastError['status'])) {
                    $errorMessage .= "HTTP {$lastError['status']}: ";
                    $errorBody = json_decode($lastError['body'] ?? '', true);
                    if (isset($errorBody['error']['message'])) {
                        $errorMessage .= $errorBody['error']['message'];
                    } else {
                        $errorMessage .= 'API request failed';
                    }
                } elseif (isset($lastError['json_error'])) {
                    $errorMessage .= "Failed to parse response: {$lastError['json_error']}";
                } elseif (isset($lastError['exception'])) {
                    $errorMessage .= $lastError['exception'];
                } else {
                    $errorMessage .= 'Unexpected error occurred';
                }
            } else {
                $errorMessage .= 'All models failed without error details';
            }
            
            Log::error('All Gemini models failed', ['last_error' => $lastError]);
            throw new \Exception($errorMessage);

        } catch (\Exception $e) {
            // Re-throw if it's already our custom exception
            if (str_contains($e->getMessage(), 'Gemini API key') || str_contains($e->getMessage(), 'Failed to generate')) {
                throw $e;
            }
            
            // Log and wrap unexpected exceptions
            Log::error('Exception during AI test generation', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new \Exception('Failed to generate test steps: ' . $e->getMessage());
        }
    }

    /**
     * Build the prompt for Gemini AI.
     *
     * @param string $description
     * @param string|null $websiteUrl
     * @return string
     */
    private function buildPrompt(string $description, ?string $websiteUrl): string
    {
        $context = $websiteUrl ? "Website URL: {$websiteUrl}\n" : "";
        
        return <<<PROMPT
You are a test automation expert. Generate test steps for a website testing scenario.

{$context}Test Description: {$description}

Generate a JSON object with a "steps" array containing test steps. Each step must be an object with the following structure based on the action type:

Available Actions:
1. "visit" - Navigate to a URL
   Required fields: action, url
   Example: {"action": "visit", "url": "/login"}

2. "type" - Type text into an input field
   Required fields: action, selector, value
   Example: {"action": "type", "selector": "input[name='email']", "value": "test@example.com"}

3. "click" - Click an element
   Required fields: action, selector
   Example: {"action": "click", "selector": "button[type='submit']"}

4. "assert_url" - Assert the current URL matches expected value
   Required fields: action, value
   Example: {"action": "assert_url", "value": "/dashboard"}

5. "assert_text" - Assert that text appears on the page
   Required fields: action, value
   Example: {"action": "assert_text", "value": "Welcome"}

6. "assert_status" - Assert HTTP status code
   Required fields: action, value
   Example: {"action": "assert_status", "value": 200}

Guidelines:
- Use common CSS selectors (e.g., input[name='field'], button[type='submit'], #id, .class)
- Generate realistic test data (emails, names, etc.)
- Include appropriate assertions to verify the test outcome
- Start with a "visit" action to navigate to the relevant page
- End with assertions to verify success
- Generate 3-8 steps depending on complexity
- Use relative URLs (starting with /) for the visit action

Return ONLY valid JSON in this exact format:
{
  "steps": [
    {"action": "visit", "url": "/example"},
    {"action": "type", "selector": "input[name='field']", "value": "test value"},
    {"action": "click", "selector": "button[type='submit']"},
    {"action": "assert_text", "value": "Success message"}
  ]
}
PROMPT;
    }

}

