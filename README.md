# LibSQL Driver for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/nick-potts/libsql-driver.svg?style=flat-square)](https://packagist.org/packages/nick-potts/libsql-driver)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/nick-potts/libsql-driver/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/nick-potts/libsql-driver/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/nick-potts/libsql-driver/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/nick-potts/libsql-driver/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/nick-potts/libsql-driver.svg?style=flat-square)](https://packagist.org/packages/nick-potts/libsql-driver)

**This package is WIP and is not ready for use.**

## Installation

You can install the package via composer:

```bash
composer require nick-potts/libsql-driver
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="libsql-driver-config"
```

## Usage

TODO: Write usage instructions

but tldr, you need to create a config in the database config file that looks like this:

```php
return [
    'connections' => [
        'libsql' => [
            'driver' => 'libsql',
            tbd
``` 

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.


## Credits

- [Nick Potts](https://github.com/nick-potts)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
