<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Config;
use Innobrain\Markitdown\Exceptions\MarkitdownException;

beforeEach(function (): void {
    // Store original config values
    $this->originalUseVenv = Config::get('markitdown.use_venv_package');
    $this->originalExecutable = Config::get('markitdown.executable');
});

afterEach(function (): void {
    // Restore original config values
    Config::set('markitdown.use_venv_package', $this->originalUseVenv);
    Config::set('markitdown.executable', $this->originalExecutable);
});

function getExecutablePath(Innobrain\Markitdown\Markitdown $markitdown): string
{
    $reflection = new ReflectionClass($markitdown);
    $reflectionProperty = $reflection->getProperty('executable');
    $reflectionProperty->setAccessible(true);

    return $reflectionProperty->getValue($markitdown);
}

it('uses venv package by default', function (): void {
    expect(Config::get('markitdown.use_venv_package'))->toBeTrue();
});

it('uses correct venv executable path when venv is enabled', function (): void {
    Config::set('markitdown.use_venv_package', true);

    $markitdown = new Innobrain\Markitdown\Markitdown;
    expect(getExecutablePath($markitdown))->toContain('/python/venv/bin/markitdown');
});

it('uses config executable path when venv is disabled', function (): void {
    Config::set('markitdown.use_venv_package', false);
    Config::set('markitdown.executable', '/usr/local/bin/markitdown');

    $markitdown = new Innobrain\Markitdown\Markitdown;
    expect(getExecutablePath($markitdown))->toBe('/usr/local/bin/markitdown');
});

it('throws exception when venv directory is missing', function (): void {
    Config::set('markitdown.use_venv_package', true);
    $venvPath = __DIR__.'/../python/venv';

    // Backup and remove venv directory
    if (is_dir($venvPath)) {
        rename($venvPath, $venvPath.'_backup');
    }

    try {
        expect(fn (): Innobrain\Markitdown\Markitdown => new Innobrain\Markitdown\Markitdown)
            ->toThrow(MarkitdownException::class, 'The path to the python script is invalid');
    } finally {
        // Ensure cleanup happens even if test fails
        if (is_dir($venvPath.'_backup')) {
            rename($venvPath.'_backup', $venvPath);
        }
    }
});

it('throws exception when markitdown executable is missing from venv', function (): void {
    Config::set('markitdown.use_venv_package', true);
    $markitdownPath = __DIR__.'/../python/venv/bin/markitdown';

    // Backup and remove markitdown executable
    if (file_exists($markitdownPath)) {
        rename($markitdownPath, $markitdownPath.'_backup');
    }

    try {
        expect(fn (): Innobrain\Markitdown\Markitdown => new Innobrain\Markitdown\Markitdown)
            ->toThrow(MarkitdownException::class, 'The path to the python script is invalid');
    } finally {
        // Ensure cleanup happens even if test fails
        if (file_exists($markitdownPath.'_backup')) {
            rename($markitdownPath.'_backup', $markitdownPath);
        }
    }
});

it('verifies venv is properly set up after package installation', function (): void {
    $venvPath = __DIR__.'/../python/venv';

    expect(is_dir($venvPath))->toBeTrue('Virtual environment directory should exist')
        ->and(is_file($venvPath.'/bin/python'))->toBeTrue('Python executable should exist in venv')
        ->and(is_file($venvPath.'/bin/pip'))->toBeTrue('Pip should exist in venv')
        ->and(is_file($venvPath.'/bin/markitdown'))->toBeTrue('Markitdown executable should exist in venv');
});
