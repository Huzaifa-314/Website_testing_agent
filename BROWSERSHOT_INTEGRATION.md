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
