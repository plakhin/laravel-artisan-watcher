# CLAUDE.md - Laravel Artisan Watcher

## Build/Test/Lint Commands
- Run all tests: `composer test`
- Run specific test: `composer test:unit -- --filter=testName`
- Run single test file: `composer test:unit tests/Path/To/TestFile.php`
- Lint code: `composer lint`
- Check code style: `composer test:lint`
- Static analysis: `composer test:types`
- Code refactoring: `composer refactor`

## Code Style Guidelines
- Use strict typing (`declare(strict_types=1)`) in all files
- Classes should be final when possible
- Full code coverage (min 100%) required for all code
- Follow PSR-12 with Laravel preset customizations
- Use type declarations for all method parameters and return types
- Import classes, constants, and functions from global namespace
- Group imports alphabetically
- Place braces on same line for control structures, next line for classes/functions
- Use early returns to reduce nesting
- Use proper DocBlocks for all public methods and properties
- Avoid multiple statements per line
- Order interfaces and traits alphabetically
- Prefer strong typing and type casting