# API Summary - Minimal Backend Testing API

## Quick Overview

**Total Endpoints Required: 2**

## Endpoints

### 1. Execute Full Test (Synchronous)
```
POST /api/test/execute
```
- Executes all test steps at once
- Returns complete results
- Used for quick test runs

### 2. Execute Single Step (Asynchronous/Progressive)
```
POST /api/test/execute-step
```
- Executes one step at a time
- Used for progressive execution with polling
- Supports browser state persistence

## Supported Actions (6 total)

1. **visit** - Navigate to URL
2. **type** - Type text into input field
3. **click** - Click on element
4. **assert_url** - Assert URL matches expected
5. **assert_text** - Assert element contains text
6. **assert_status** - Assert HTTP status code

## Key Data Structures

### Test Step Format
```json
{
  "action": "visit|type|click|assert_url|assert_text|assert_status",
  "url": "string (for visit)",
  "selector": "string (for type, click, assert_text)",
  "value": "string (for type, assert_url, assert_text, assert_status)"
}
```

### Request Format (Execute)
```json
{
  "test_run_id": "123",
  "website_url": "https://example.com",
  "steps": [...],
  "expected_result": "string",
  "options": {
    "headless": true,
    "timeout": 30000,
    "screenshot_on_failure": true
  }
}
```

### Response Format
```json
{
  "success": true|false,
  "test_run_id": "123",
  "status": "running|completed",
  "result": "pass|fail|null",
  "current_step": 3,
  "total_steps": 6,
  "logs": ["...", "..."],
  "executed_at": "ISO8601",
  "screenshots": [],
  "error": null|{...}
}
```

## Integration Points

### Laravel Service Update
- Update `TestExecutionService::run()` to call `POST /api/test/execute`
- Update `TestExecutionService::executeStep()` to call `POST /api/test/execute-step`
- Store API URL in `.env`: `TEST_EXECUTION_API_URL=http://localhost:3000`

## Implementation Phases

**Phase 1 (MVP):**
- ✅ Execute endpoint
- ✅ visit, type, click actions
- ✅ Basic error handling

**Phase 2:**
- ✅ Assertions (assert_url, assert_text, assert_status)

**Phase 3:**
- ✅ Execute-step endpoint
- ✅ Progressive execution

**Phase 4:**
- Screenshots
- AI integration
- Advanced features

## See Full Documentation

For complete API documentation with examples, request/response formats, and integration guide, see [API_DOCUMENTATION.md](./API_DOCUMENTATION.md).

