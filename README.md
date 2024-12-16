# Laravel bindings for markitdown.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/innobrain/markitdown.svg?style=flat-square)](https://packagist.org/packages/innobrain/markitdown)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/innobrain/markitdown/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/innobrain/markitdown/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/innobrain/markitdown/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/innobrain/markitdown/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/innobrain/markitdown.svg?style=flat-square)](https://packagist.org/packages/innobrain/markitdown)

Laravel bindings for markitdown.

## Installation

You can install the package via composer:

```bash
composer require innobrain/markitdown
```

### Install Markitdown

Install the markitdown package from pip.

```bash
pip install markitdown
```

⚡ Recommended way is to use `pipx`:

On macOS:
```
brew install pipx
pipx ensurepath
sudo pipx ensurepath --global # optional to allow pipx actions with --global argument``
```

Or see how to install on [other platforms](https://github.com/pypa/pipx).
After installling `pipx`, you can install `markitdown` with:

```bash
pipx install markitdown
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="markitdown-migrations"
php artisan migrate
```

### Publishing things

You can publish the config file with:

```bash
php artisan vendor:publish --tag="markitdown-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="markitdown-views"
```

## Usage

```php
$markdown = \Innobrain\Markitdown\Facades\Markitdown::convert('/path/to/file.docx');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Konstantin Auffinger](https://github.com/kauffinger)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
