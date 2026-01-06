# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Build & Test Commands

```bash
composer test          # Run PHPUnit tests
composer cs            # Check coding style (PHP_CodeSniffer)
composer cs-fix        # Fix coding style violations
composer sa            # Static analysis (PHPStan + Psalm)
composer tests         # Run cs, sa, and test together
composer build         # Full build: clean, cs, sa, coverage, metrics
composer coverage      # Generate test coverage report (xdebug)
composer pcov          # Generate test coverage report (pcov)
```

Run a single test:
```bash
./vendor/bin/phpunit --filter testMethodName
./vendor/bin/phpunit tests/StubJsonTest.php
```

## Architecture

BEAR.StubJson is a Ray.Di module that stubs BEAR.Resource objects with JSON files during development, allowing frontend work to proceed before backend implementation is complete.

**Core Components:**
- `StubJsonModule` - Ray.Di module that installs the AOP interceptor on all ResourceObject subclasses
- `StubJsonInterceptor` - Intercepts methods starting with `on` (onGet, onPost, etc.) and returns JSON file contents instead of executing the method, if a corresponding JSON file exists
- `JsonRootPath` - Ray.Di qualifier attribute for injecting the JSON directory path

**How it works:**
1. The module binds an interceptor to all `ResourceObject::on*` methods
2. When a resource method is called, the interceptor checks for a JSON file at `{jsonRootPath}/{ResourceType}/{ResourceName}.json`
3. If the JSON file exists, its contents become the resource body; otherwise, the original method executes

## Coding Standards

- PSR-12 + Doctrine coding standard (see `phpcs.xml`)
- PHP 8.1+ with strict types
- Uses PHP attributes for DI qualifiers (e.g., `#[JsonRootPath]`)
