# Website Testing Backend API - Minimal API Documentation

## Overview

This document defines the minimal set of APIs required for a backend testing server that will execute website tests using AI and browser automation. The backend API server will be called by the Laravel application to perform actual website testing.

## Architecture

```
Laravel Application (Frontend)
    ↓ HTTP Request
Backend Testing API Server
    ↓ Browser Automation (Puppeteer/Playwright)
    ↓ AI Integration (OpenAI/Anthropic/Gemini)
Real Website
```

## Data Flow

1. **Laravel** creates a `TestCase` with test steps (JSON array)
2. **Laravel** calls the backend API with test execution request
3. **Backend API** executes test steps using browser automation
4. **Backend API** returns execution results (logs, status, result)
5. **Laravel** updates `TestRun` model with results

## Minimal API Endpoints

### 1. Execute Full Test (Synchronous)

**Endpoint:** `POST /api/test/execute`

**Purpose:** Execute all test steps synchronously and return complete results.

**Request Body:**
```json
{
  "test_run_id": "123",
  "website_url": "https://example.com",
  "steps": [
    {
      "action": "visit",
      "url": "/login"
    },
    {
      "action": "type",
      "selector": "input[name='email']",
      "value": "test@example.com"
    },
    {
      "action": "type",
      "selector": "input[name='password']",
      "value": "password123"
    },
    {
      "action": "click",
      "selector": "button[type='submit']"
    },
    {
      "action": "assert_url",
      "value": "/dashboard"
    },
    {
      "action": "assert_text",
      "selector": "h1",
      "value": "Welcome"
    }
  ],
  "expected_result": "Login successful",
  "options": {
    "headless": true,
    "timeout": 30000,
    "screenshot_on_failure": true
  }
}
```

**Response (Success):**
```json
{
  "success": true,
  "test_run_id": "123",
  "status": "completed",
  "result": "pass",
  "current_step": 6,
  "total_steps": 6,
  "logs": [
    "[INFO] Starting browser session...",
    "[INFO] Browser: Chrome/Headless",
    "[STEP 1] Executing: Visit",
    "[STEP 1] Navigating to: /login",
    "[STEP 1] Page loaded successfully",
    "[STEP 1] ✓ URL visited successfully",
    "[STEP 2] Executing: Type",
    "[STEP 2] Locating element: input[name='email']",
    "[STEP 2] Text entered successfully",
    "[STEP 2] ✓ Typing completed",
    "[STEP 3] Executing: Type",
    "[STEP 3] Locating element: input[name='password']",
    "[STEP 3] Text entered successfully",
    "[STEP 3] ✓ Typing completed",
    "[STEP 4] Executing: Click",
    "[STEP 4] Locating element: button[type='submit']",
    "[STEP 4] ✓ Click action completed",
    "[STEP 5] Executing: Assert url",
    "[STEP 5] Current URL: /dashboard",
    "[STEP 5] ✓ URL assertion passed",
    "[STEP 6] Executing: Assert text",
    "[STEP 6] Locating element: h1",
    "[STEP 6] ✓ Text assertion passed",
    "[INFO] All steps completed successfully",
    "[INFO] Execution finished. Result: PASS"
  ],
  "executed_at": "2026-01-07T10:30:00Z",
  "screenshots": [],
  "error": null
}
```

**Response (Failure):**
```json
{
  "success": false,
  "test_run_id": "123",
  "status": "completed",
  "result": "fail",
  "current_step": 4,
  "total_steps": 6,
  "logs": [
    "[INFO] Starting browser session...",
    "[STEP 1] ✓ URL visited successfully",
    "[STEP 2] ✓ Typing completed",
    "[STEP 3] ✓ Typing completed",
    "[STEP 4] ✓ Click action completed",
    "[STEP 5] Executing: Assert url",
    "[STEP 5] Current URL: /login",
    "[ERROR] Step 5 FAILED: URL assertion failed. Expected '/dashboard' but got '/login'",
    "[ERROR] Execution aborted due to failure",
    "[INFO] Execution finished. Result: FAIL"
  ],
  "executed_at": "2026-01-07T10:30:15Z",
  "screenshots": ["base64_encoded_screenshot_data"],
  "error": {
    "step": 5,
    "action": "assert_url",
    "message": "URL assertion failed. Expected '/dashboard' but got '/login'"
  }
}
```

---

### 2. Execute Single Step (Asynchronous/Progressive)

**Endpoint:** `POST /api/test/execute-step`

**Purpose:** Execute a single step of a test run. Used for progressive/async execution where Laravel polls for progress.

**Request Body:**
```json
{
  "test_run_id": "123",
  "website_url": "https://example.com",
  "current_step": 2,
  "steps": [
    {
      "action": "visit",
      "url": "/login"
    },
    {
      "action": "type",
      "selector": "input[name='email']",
      "value": "test@example.com"
    },
    {
      "action": "type",
      "selector": "input[name='password']",
      "value": "password123"
    },
    {
      "action": "click",
      "selector": "button[type='submit']"
    }
  ],
  "previous_state": {
    "url": "https://example.com/login",
    "cookies": [],
    "localStorage": {}
  },
  "options": {
    "headless": true,
    "timeout": 30000
  }
}
```

**Response (Step Executed - Still Running):**
```json
{
  "success": true,
  "test_run_id": "123",
  "step_index": 2,
  "status": "running",
  "result": null,
  "current_step": 3,
  "total_steps": 4,
  "logs": [
    "[STEP 3] Executing: Type",
    "[STEP 3] Locating element: input[name='password']",
    "[STEP 3] Text entered successfully",
    "[STEP 3] ✓ Typing completed"
  ],
  "next_state": {
    "url": "https://example.com/login",
    "cookies": [],
    "localStorage": {}
  },
  "screenshot": null,
  "error": null
}
```

**Response (Step Executed - Test Completed):**
```json
{
  "success": true,
  "test_run_id": "123",
  "step_index": 3,
  "status": "completed",
  "result": "pass",
  "current_step": 4,
  "total_steps": 4,
  "logs": [
    "[STEP 4] Executing: Click",
    "[STEP 4] Locating element: button[type='submit']",
    "[STEP 4] ✓ Click action completed",
    "[INFO] All steps completed successfully",
    "[INFO] Execution finished. Result: PASS"
  ],
  "next_state": {
    "url": "https://example.com/dashboard",
    "cookies": [
      {
        "name": "session",
        "value": "abc123",
        "domain": "example.com"
      }
    ],
    "localStorage": {}
  },
  "screenshot": null,
  "error": null
}
```

**Response (Step Failed):**
```json
{
  "success": false,
  "test_run_id": "123",
  "step_index": 2,
  "status": "completed",
  "result": "fail",
  "current_step": 3,
  "total_steps": 4,
  "logs": [
    "[STEP 3] Executing: Type",
    "[STEP 3] Locating element: input[name='password']",
    "[ERROR] Step 3 FAILED: Element not found: input[name='password']",
    "[ERROR] Execution aborted due to failure",
    "[INFO] Execution finished. Result: FAIL"
  ],
  "next_state": {
    "url": "https://example.com/login",
    "cookies": [],
    "localStorage": {}
  },
  "screenshot": "base64_encoded_screenshot_data",
  "error": {
    "step": 3,
    "action": "type",
    "message": "Element not found: input[name='password']"
  }
}
```

---

## Supported Actions

### 1. `visit`
Navigate to a URL.

**Required Fields:**
- `action`: "visit"
- `url`: string (relative or absolute URL)

**Example:**
```json
{
  "action": "visit",
  "url": "/login"
}
```

---

### 2. `type`
Type text into an input field.

**Required Fields:**
- `action`: "type"
- `selector`: string (CSS selector)
- `value`: string (text to type)

**Example:**
```json
{
  "action": "type",
  "selector": "input[name='email']",
  "value": "test@example.com"
}
```

---

### 3. `click`
Click on an element.

**Required Fields:**
- `action`: "click"
- `selector`: string (CSS selector)

**Example:**
```json
{
  "action": "click",
  "selector": "button[type='submit']"
}
```

---

### 4. `assert_url`
Assert that the current URL matches expected value.

**Required Fields:**
- `action`: "assert_url"
- `value`: string (expected URL or URL pattern)

**Example:**
```json
{
  "action": "assert_url",
  "value": "/dashboard"
}
```

---

### 5. `assert_text`
Assert that an element contains expected text.

**Required Fields:**
- `action`: "assert_text"
- `value`: string (expected text)
- `selector`: string (CSS selector, optional, defaults to "body")

**Example:**
```json
{
  "action": "assert_text",
  "selector": "h1",
  "value": "Welcome"
}
```

---

### 6. `assert_status`
Assert that the HTTP status code matches expected value.

**Required Fields:**
- `action`: "assert_status"
- `value`: number (expected HTTP status code)

**Example:**
```json
{
  "action": "assert_status",
  "value": 200
}
```

---

## Request/Response Fields

### Request Fields

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `test_run_id` | string | Yes | Laravel TestRun ID for tracking |
| `website_url` | string | Yes | Base URL of the website to test |
| `steps` | array | Yes | Array of test step objects |
| `current_step` | number | No* | Current step index (0-based). Required for execute-step endpoint |
| `previous_state` | object | No | Browser state from previous step (for progressive execution) |
| `expected_result` | string | No | Expected test result description |
| `options` | object | No | Execution options (headless, timeout, etc.) |

*Required only for `execute-step` endpoint

### Response Fields

| Field | Type | Description |
|-------|------|-------------|
| `success` | boolean | Whether the request was successful |
| `test_run_id` | string | Laravel TestRun ID |
| `status` | string | Test status: "running" or "completed" |
| `result` | string\|null | Test result: "pass", "fail", or null (if still running) |
| `current_step` | number | Number of steps completed |
| `total_steps` | number | Total number of steps |
| `logs` | array | Array of log messages (strings) |
| `executed_at` | string | ISO 8601 timestamp of execution |
| `screenshots` | array | Array of base64-encoded screenshots (on failure) |
| `error` | object\|null | Error object if test failed |
| `next_state` | object | Browser state after step execution (for execute-step) |
| `screenshot` | string\|null | Base64-encoded screenshot (for execute-step) |

---

## Error Handling

### HTTP Status Codes

- `200 OK`: Request successful
- `400 Bad Request`: Invalid request body or parameters
- `500 Internal Server Error`: Server error during execution

### Error Response Format

```json
{
  "success": false,
  "error": {
    "code": "ELEMENT_NOT_FOUND",
    "message": "Element not found: input[name='email']",
    "step": 2,
    "action": "type"
  }
}
```

### Common Error Scenarios

1. **Element Not Found**: Selector doesn't match any element
2. **Timeout**: Page or element didn't load within timeout period
3. **Assertion Failed**: Assertion didn't pass
4. **Navigation Error**: Failed to navigate to URL
5. **Network Error**: Failed to connect to website

---

## Integration with Laravel

### Laravel TestExecutionService Update

The `TestExecutionService` class needs to be updated to call the backend API instead of simulating execution:

```php
// app/Services/TestExecutionService.php

public function run(TestCase $testCase, ?TestRun $testRun = null): TestRun
{
    // Create test run if not provided
    if (!$testRun) {
        $testRun = $testCase->testRuns()->create([
            'status' => 'running',
            'current_step' => 0,
            'total_steps' => count($testCase->steps),
            'result' => null,
            'logs' => [],
            'executed_at' => now(),
        ]);
    }

    // Call backend API
    $websiteUrl = $testCase->testDefinition->website->url;
    $response = Http::post(config('services.test_execution_api.url') . '/api/test/execute', [
        'test_run_id' => $testRun->id,
        'website_url' => $websiteUrl,
        'steps' => $testCase->steps,
        'expected_result' => $testCase->expected_result,
        'options' => [
            'headless' => true,
            'timeout' => 30000,
            'screenshot_on_failure' => true,
        ],
    ]);

    if ($response->successful()) {
        $data = $response->json();
        
        // Update test run with results
        $testRun->update([
            'status' => $data['status'],
            'current_step' => $data['current_step'],
            'total_steps' => $data['total_steps'],
            'result' => $data['result'],
            'logs' => $data['logs'],
        ]);
    } else {
        // Handle error
        $testRun->update([
            'status' => 'completed',
            'result' => 'fail',
            'logs' => array_merge($testRun->logs ?? [], [
                '[ERROR] Backend API call failed: ' . $response->body()
            ]),
        ]);
    }

    return $testRun;
}

public function executeStep(TestRun $testRun): TestRun
{
    $testCase = $testRun->testCase;
    $websiteUrl = $testCase->testDefinition->website->url;
    
    // Call backend API for single step execution
    $response = Http::post(config('services.test_execution_api.url') . '/api/test/execute-step', [
        'test_run_id' => $testRun->id,
        'website_url' => $websiteUrl,
        'current_step' => $testRun->current_step ?? 0,
        'steps' => $testCase->steps,
        'previous_state' => $testRun->previous_state ?? null,
        'options' => [
            'headless' => true,
            'timeout' => 30000,
        ],
    ]);

    if ($response->successful()) {
        $data = $response->json();
        
        // Update test run
        $testRun->update([
            'status' => $data['status'],
            'current_step' => $data['current_step'],
            'total_steps' => $data['total_steps'],
            'result' => $data['result'],
            'logs' => array_merge($testRun->logs ?? [], $data['logs']),
            'previous_state' => $data['next_state'] ?? null,
        ]);
    }

    return $testRun->fresh();
}
```

### Environment Configuration

Add to `.env`:
```env
TEST_EXECUTION_API_URL=http://localhost:3000
```

Add to `config/services.php`:
```php
'test_execution_api' => [
    'url' => env('TEST_EXECUTION_API_URL', 'http://localhost:3000'),
],
```

---

## Implementation Priority

### Phase 1: Core Functionality (Minimum Viable)
1. ✅ `POST /api/test/execute` endpoint
2. ✅ Support for actions: `visit`, `type`, `click`
3. ✅ Basic error handling
4. ✅ Logging

### Phase 2: Assertions
1. ✅ Support for `assert_url`, `assert_text`, `assert_status`
2. ✅ Proper assertion error messages

### Phase 3: Progressive Execution
1. ✅ `POST /api/test/execute-step` endpoint
2. ✅ Browser state management
3. ✅ Step-by-step execution

### Phase 4: Enhanced Features
1. Screenshot capture on failures
2. AI integration for element finding
3. Advanced error recovery
4. Performance optimizations

---

## Summary

**Minimal API Set:**
- **2 Endpoints**:
  1. `POST /api/test/execute` - Synchronous full test execution
  2. `POST /api/test/execute-step` - Asynchronous single step execution

**6 Supported Actions:**
- `visit` - Navigate to URL
- `type` - Type text into input
- `click` - Click element
- `assert_url` - Assert URL matches
- `assert_text` - Assert text content
- `assert_status` - Assert HTTP status

This minimal API set provides everything needed to replace the mock test execution with real browser automation while maintaining compatibility with the existing Laravel application structure.

