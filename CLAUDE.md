# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Development Commands

### Testing

- `composer test` - Run the full test suite using Pest
- `php vendor/bin/pest` - Run Pest tests directly

### Code Quality

- `composer lint` - Run both Pint code formatting and PHPStan static analysis
- `php vendor/bin/pint` - Code formatting with Laravel preset
- `php vendor/bin/phpstan analyse --verbose` - Static analysis
- `php vendor/bin/phpstan analyse --memory-limit=2G` - Static analysis with increased memory

### Development Server

- `composer serve` - Start development server using Testbench
- `composer build` - Build the Testbench workbench

### Package Management

- `composer install` - Install PHP dependencies
- `pnpm install` - Install Node.js dependencies (for tooling)

## Architecture Overview

This is a Laravel package for interacting with the Japan Post API, providing address and postal code search functionality.

### Core Components

**Service Classes** (`src/`):

- `Token` - Handles API authentication and token management
- `AddressZip` - Searches addresses by criteria (prefecture, city, street)
- `SearchCode` - Searches addresses by postal code
- `ServiceProvider` - Laravel service provider with dependency injection bindings

**Exception Handling** (`src/Exceptions/`):

- `Exception` - Base exception class
- `HttpException` - HTTP request failures
- `AddressesNotFoundException` - No results found for searches

**Configuration** (`config/services.php`):

- Requires `JAPANPOST_CLIENT_ID` and `JAPANPOST_SECRET_KEY` environment variables

### Dependency Injection

The package provides Laravel container bindings:

- `japanpost.token` → `Token::class`
- `japanpost.address_zip` → `AddressZip::class`
- `japanpost.search_code` → `SearchCode::class`

### HTTP Client Pattern

All service classes follow the same pattern:

- Use Guzzle HTTP client with `https://api.da.pf.japanpost.jp/` base URI
- Support custom Guzzle options via `setGuzzleOptions()`
- Automatic token management (except `Token` class itself)
- Bearer token authentication for protected endpoints

### Testing Framework

Uses Pest PHP with Orchestra Testbench for Laravel package testing. Test structure follows:

- `tests/Feature/` - Integration tests
- `tests/Unit/` - Unit tests
- `tests/TestCase.php` - Base test case setup
- `tests/Pest.php` - Pest configuration with TestCase extension

**Test Writing Guidelines:**

- Use Pest's `test()` function for defining tests
- Use `expect()` for assertions instead of PHPUnit assertions
- Test files should not include `uses(TestCase::class)` as it's configured globally
- Focus on descriptive test names that explain the behavior being tested
- Use closures for test logic with proper dependency injection

**Test Categories:**

- **Unit Tests**: Test individual classes and methods in isolation
- **Feature Tests**: Test integration between components and Laravel service provider
- **Exception Tests**: Verify proper exception handling and error cases

## Code Style

### PHP Code Style

- Uses Laravel Pint preset with strict formatting rules
- Requires strict type declarations (`declare(strict_types=1)`)
- Enforces fully qualified strict types
- Ordered class elements and interfaces
- Protected properties should be private when possible

### Commit Hooks

- Uses Husky + lint-staged for pre-commit checks
- Automatic PHP code formatting and static analysis
- Prettier formatting for non-PHP files

## API Integration Notes

- Base URI: `https://api.da.pf.japanpost.jp/`
- Authentication: Client credentials flow → Bearer token
- Token endpoint: `POST /api/v1/j/token`
- Address search: `POST /api/v1/addresszip`
- Postal code search: `GET /api/v1/searchcode/{code}`
- Requires `x-forwarded-for` header for token requests
- Uses `getClientIP()` helper function from `src/helpers.php`

## Development Workflow

1. Make code changes
2. Run `composer lint` to ensure code quality
3. Run `composer test` to verify functionality
4. Commit changes (hooks will automatically run linting)
