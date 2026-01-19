# Browsershot Integration

## Overview

Browsershot provides browser automation within Laravel by wrapping Puppeteer (Node.js) to control headless Chrome/Chromium browsers for real website testing.

## Architecture

```
Laravel TestExecutionService → Browsershot (PHP) → Puppeteer (Node.js) → Headless Chrome → Real Website
```

## AI Integration

- **Model**: `gemini-2.5-flash` (cost-effective, fast, good for structured output)
- Used for generating test steps from descriptions

### Hybrid Test Generation Approach

**Phase 1: Fast Generation (No Browser)**
- AI generates test steps from natural language description
- Uses common patterns and heuristics
- Fast and cost-effective

**Phase 2: Optional Selector Validation (With Browser)**
- Browsershot validates selectors exist on the page
- Automatically fixes broken selectors
- User can skip for faster workflow

**Phase 3: Runtime Fallback**
- If selector fails during execution, AI discovers alternatives
- Self-healing tests with automatic selector refinement

## Available Methods

| Action | Browsershot Method |
|--------|-------------------|
| `visit` | `Browsershot::url($url)` |
| `type` | `->type($selector, $value)` |
| `click` | `->click($selector)` |
| `assert_url` | `->getCurrentUrl()` |
| `assert_text` | `->text($selector)` |
| `assert_status` | Check HTTP response |

## Dependencies

- `spatie/browsershot` (PHP package)
- `puppeteer` (Node.js package via npm)
- Node.js runtime

## Integration Points

- **TestExecutionService**: Replace mock execution with Browsershot calls
- **Laravel Queues**: Already configured for async execution
- **Database**: Direct access to TestRun model for results
