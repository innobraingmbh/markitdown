# Laravel bindings for markitdown.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/innobrain/markitdown.svg?style=flat-square)](https://packagist.org/packages/innobrain/markitdown)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/innobraingmbh/markitdown/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/innobraingmbh/markitdown/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/innobraingmbh/markitdown/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/innobraingmbh/markitdown/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/innobrain/markitdown.svg?style=flat-square)](https://packagist.org/packages/innobrain/markitdown)

Laravel bindings for markitdown.

## Installation

You can install the package via composer:

```bash
composer require innobrain/markitdown
```

After installing the package, make sure to publish the configuration file:

```bash
php artisan vendor:publish --tag="markitdown-config"
```

The package will automatically set up a Python virtual environment with the required dependencies during the Laravel package registration process.

### Alternative Installation Methods

If you prefer not to use the built-in virtual environment, you can disable it in your `.env` file:

```bash
MARKITDOWN_USE_VENV_PACKAGE=false
```

Then install markitdown manually using one of these methods:

#### Using pip directly:

```bash
pip install markitdown
```

#### Using pipx (Recommended for manual installation):

On macOS:
```bash
brew install pipx
pipx ensurepath
sudo pipx ensurepath --global # optional to allow pipx actions with --global argument
```

Or see how to install on [other platforms](https://github.com/pypa/pipx).
After installing `pipx`, you can install `markitdown` with:

```bash
pipx install markitdown
```

When not using the built-in virtual environment, you'll need to set the path to the `markitdown` executable in your `.env` file. You can retrieve the path with:
```bash
which markitdown
```

```bash
MARKITDOWN_EXECUTABLE=/path/to/markitdown
```

Also, when running the script anywhere but the console, you need to set the PATH, as php-fpm does not have
access to the PATH variable. You can do this by adding the following to your `.env` file:

```bash
echo $PATH
```

```bash
MARKITDOWN_SYSTEM_PATH=<your path>
```

## Configuration

The package's configuration will be published to `config/markitdown.php`. Here are the available options:

```php
return [
    /*
     * Use this to set the timeout for the process. Default is 30 seconds.
     */
    'process_timeout' => env('MARKITDOWN_PROCESS_TIMEOUT', 30),

    /*
     * Use this to set the path to the markitdown executable. If not set,
     * the binary will be searched in the PATH.
     */
    'executable' => env('MARKITDOWN_EXECUTABLE', 'markitdown'),

    /*
     * This will override the above setting and use the new locally installed package.
     */
    'use_venv_package' => env('MARKITDOWN_USE_VENV_PACKAGE', true),

    /*
     * This is needed when you want to run markitdown in php-fpm. One dependency
     * of markitdown requires PATH to be set. If you are running in a console,
     * this is not needed.
     */
    'system' => [
        'path' => env('MARKITDOWN_SYSTEM_PATH', ''),
    ],

    /*
     * The path where temporary files will be stored. This directory must be writable
     * by the web server. Defaults to storage/app/private/markitdown_tmp
     */
    'temporary_directory' => env('MARKITDOWN_TEMPORARY_DIRECTORY', storage_path('app/private/markitdown_tmp')),
];
```

## Usage

```php
// convert a file:
$markdown = \Innobrain\Markitdown\Facades\Markitdown::convert('/path/to/file.docx');

// or convert a file you already have in memory:
$file = file_get_contents('/path/to/file.docx');
$markdown = \Innobrain\Markitdown\Facades\Markitdown::convertFile($file, '.docx');
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
