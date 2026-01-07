# BEAR.FakeJson

Fake BEAR.Resource responses with JSON files for frontend development.

## Overview

This module allows frontend development to proceed before backend implementation is complete by faking resource responses with JSON files. It only supports happy path scenarios - for error handling and edge cases, use the actual backend implementation.

## Benefits

* Develop frontend HTML templates without waiting for backend
* Preview final output with realistic data
* Enable parallel frontend/backend development
* Use JSON as a shared contract between teams

## Installation

```bash
composer require bear/fake-json
```

## Module Setup

Create a `FakeModule` for the fake context:

```php
// src/Module/FakeModule.php
namespace MyVendor\MyApp\Module;

use BEAR\FakeJson\FakeJsonModule;
use Ray\Di\AbstractModule;

class FakeModule extends AbstractModule
{
    protected function configure(): void
    {
        $this->install(new FakeJsonModule(__DIR__ . '/../../var/fake'));
    }
}
```

Then use the `fake-app` context to enable fake responses:

```bash
php bin/page.php get /
# Uses actual backend

php -d "BEAR_CONTEXTS=fake-app" bin/page.php get /
# Uses JSON files from var/fake/
```

## Usage

Create JSON files matching your resource structure:

```
var/fake/Page/Index.json
var/fake/App/User.json
```

Example `var/fake/Page/Index.json`:

```json
{
    "greeting": "Hello",
    "user": {
        "name": "John"
    }
}
```

When the resource method is called, the JSON content is returned as the resource body instead of executing the actual method. If no JSON file exists, the original method executes normally.

## Best Practices

* Install only in development context, not in production
* Keep JSON structure synchronized with actual resource output
* Use realistic data that matches production scenarios
* Version control JSON files as a contract between frontend and backend teams

## Limitations

* Happy path only - does not support error responses (4xx, 5xx)
* Does not set response headers or status codes
* JSON files must be valid JSON objects (not arrays)
