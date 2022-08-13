
# Let Laravel Sanctum use cache to retrieve token

[![Latest Version on Packagist](https://img.shields.io/packagist/v/abe/cache-for-laravel-sanctum.svg?style=flat-square)](https://packagist.org/packages/abe/cache-for-laravel-sanctum)
[![run-tests](https://github.com/abrahamgreyson/cache-for-laravel-sanctum/actions/workflows/run-tests.yml/badge.svg)](https://github.com/abrahamgreyson/cache-for-laravel-sanctum/actions/workflows/run-tests.yml)
[![Check & fix styling](https://github.com/abrahamgreyson/cache-for-laravel-sanctum/actions/workflows/php-cs-fixer.yml/badge.svg?branch=main)](https://github.com/abrahamgreyson/cache-for-laravel-sanctum/actions/workflows/php-cs-fixer.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/abe/cache-for-laravel-sanctum.svg?style=flat-square)](https://packagist.org/packages/abe/cache-for-laravel-sanctum)

Laravel Sanctum brings 3 database queries every HTTP request.
This package wrap the default `PersonalAccessToken` model, use cache to retrieve the `token` and `tokenable`, added update interval for last used time(update only if current time greater than interval since last update, default to 1 hour).


## Installation

You can install the package via composer:

```bash
composer require abe/cache-for-laravel-sanctum
```

## Usage

Once you require this package in project, Sanctum token will retrieve through cache automatically.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [abraham greyson](https://github.com/abrahamgreyson)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
