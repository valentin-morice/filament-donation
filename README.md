#  A Filament panel plug-in to raise & manage funding

[![Latest Version on Packagist](https://img.shields.io/packagist/v/valentin-morice/filament-donation.svg?style=flat-square)](https://packagist.org/packages/valentin-morice/filament-donation)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/valentin-morice/filament-donation/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/valentin-morice/filament-donation/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/valentin-morice/filament-donation/fix-php-code-styling.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/valentin-morice/filament-donation/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/valentin-morice/filament-donation.svg?style=flat-square)](https://packagist.org/packages/valentin-morice/filament-donation)



This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require valentin-morice/filament-donation
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-donation-config"
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-donation-migrations"
php artisan migrate
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-donation-views"
```

## Usage

```php
$filamentDonation = new ValentinMorice\FilamentDonation();
echo $filamentDonation->echoPhrase('Hello, ValentinMorice!');
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
