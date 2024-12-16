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

You will need to have `markitdown` available as a binary in your command line.

⚡ The recommended way to do this is to use `pipx`:

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

### Publishing things

You can publish the config file with:

```bash
php artisan vendor:publish --tag="markitdown-config"
```

This is the contents of the published config file:

```php
return [
    'process_timeout' => 30,
];
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

## Credits

- [Konstantin Auffinger](https://github.com/kauffinger)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
