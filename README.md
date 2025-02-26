[![Tests](https://github.com/plakhin/laravel-artisan-watcher/actions/workflows/tests.yml/badge.svg)](https://github.com/plakhin/laravel-artisan-watcher/actions/workflows/tests.yml)

# Laravel Artisan Watcher

A Laravel package that provides file watching functionality to automatically execute a CLI or Artisan command when files change. This is useful for automatically running tests, linting, or any other command when your files are modified during development.

> [!WARNING]  
> <i>Very early alpha — pure proof of concept.</i>  
> Believe it or not, <b>~95% of this package’s code is AI-generated</b> under 10 minutes with a single prompt!  
> For the full story, check out [the PR](https://github.com/plakhin/laravel-artisan-watcher/pull/1).

## Installation

You can install the package via composer:

```bash
composer require plakhin/laravel-artisan-watcher --dev
```

## Usage

The package adds an `artisan watch` command that you can use to watch for file changes and trigger commands:

```bash
# Watch current directory and run tests when PHP files change
php artisan watch --command="php artisan test"

# Watch a specific directory for changes to JS files
php artisan watch app/Http --extensions=js --command="npm run lint"

# Watch with custom polling interval (in seconds)
php artisan watch --interval=2 --command="php artisan test"

# Exclude multiple directories
php artisan watch --exclude=vendor,node_modules,storage --command="php artisan test"
```

### Available Options

- `path`: The directory to watch (default: current directory)
- `--command`: The command to run when files change (required)
- `--interval`: Polling interval in seconds (default: 1)
- `--extensions`: Comma-separated list of file extensions to watch (default: php)
- `--exclude`: Comma-separated list of directories to exclude (default: vendor,node_modules)

## Example Use Cases

- Automatically run tests when files change:
  ```bash
  php artisan watch --command="php artisan test"
  ```

- Automatically compile assets when JavaScript or SCSS files change:
  ```bash
  php artisan watch resources --extensions=js,scss --command="npm run dev"
  ```

- Run type checking when PHP files change:
  ```bash
  php artisan watch --command="php artisan test:types"
  ```

## Contributing
Contributions are welcome, and are accepted via pull requests.
Please review these guidelines before submitting any pull requests.

### Process

1. Fork the project
1. Create a new branch
1. Code, test, commit and push
1. Open a pull request detailing your changes.

### Guidelines

* Please ensure the coding style running `composer lint`.
* Please keep the codebase modernized using automated refactors with Rector `composer refactor`.
* Send a coherent commit history, making sure each individual commit in your pull request is meaningful.
* You may need to [rebase](https://git-scm.com/book/en/v2/Git-Branching-Rebasing) to avoid merge conflicts.
* Please remember to follow [SemVer](http://semver.org/).

### Linting

```bash
composer lint
```

### Refactoring with Rector

```bash
composer refactor
```

### Testing

Run all tests:
```bash
composer test
```

Check code style:
```bash
composer test:lint
```

Check possible code improvements:
```bash
composer test:refactor
```

Check types:
```bash
composer test:types
```

Run Unit tests:
```bash
composer test:unit
```

#

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Stanislav Plakhin](https://github.com/plakhin)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
