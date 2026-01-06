# BEAR.StubJson

Stub BEAR.Resource objects with JSON files for frontend development.

## Overview

This module allows frontend development to proceed before backend implementation is complete by stubbing resource responses with JSON files. It only supports happy path scenarios - for error handling and edge cases, use the actual backend implementation.

## Benefits

* Develop frontend HTML templates without waiting for backend
* Preview final output with realistic data
* Enable parallel frontend/backend development
* Use JSON as a shared contract between teams

## Installation

```bash
composer require bear/stub-json
```

## Module Setup

```php
use BEAR\StubJson\StubJsonModule;
use Ray\Di\AbstractModule;

class AppModule extends AbstractModule
{
    protected function configure(): void
    {
        // Install for development only
        $this->install(new StubJsonModule(__DIR__ . '/var/stub'));
    }
}
```

## Usage

Create JSON files matching your resource structure:

```
var/stub/Page/Index.json
var/stub/App/User.json
```

Example `var/stub/Page/Index.json`:

```json
{
    "greeting": "Hello",
    "user": {
        "name": "John"
    }
}
```

When the resource method is called, the JSON content is returned as the resource body instead of executing the actual method. If no JSON file exists, the original method executes normally.

## Limitations

* Happy path only - does not support error responses (4xx, 5xx)
* Does not set response headers or status codes
* JSON files must be valid JSON objects (not arrays)
