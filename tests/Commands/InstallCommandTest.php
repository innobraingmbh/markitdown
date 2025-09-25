<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

beforeEach(function (): void {
    // Create a temporary directory for testing
    $this->tempDir = sys_get_temp_dir().'/markitdown_test_'.uniqid();
    File::makeDirectory($this->tempDir, 0755, true);
    File::makeDirectory($this->tempDir.'/python', 0755, true);
});

afterEach(function (): void {
    // Clean up the temporary directory
    if (File::exists($this->tempDir)) {
        File::deleteDirectory($this->tempDir);
    }
});

it('generates requirements.txt with all extras by default', function (): void {
    Config::set('markitdown.package_extras', 'all');
    Config::set('markitdown.package_version', '0.1.3');

    $requirementsPath = $this->tempDir.'/python/requirements.txt';

    // Mock the python path
    $installCommand = new Innobrain\Markitdown\Commands\InstallCommand;
    $reflection = new ReflectionClass($installCommand);

    // Mock the components property
    $reflectionProperty = $reflection->getProperty('components');
    $reflectionProperty->setValue($installCommand, new class
    {
        public function error($message): void {}

        public function info($message): void {}
    });

    $reflectionMethod = $reflection->getMethod('generateRequirements');

    $result = $reflectionMethod->invoke($installCommand, $this->tempDir.'/python');

    expect($result)->toBeTrue();
    expect(File::exists($requirementsPath))->toBeTrue();
    expect(File::get($requirementsPath))->toBe("markitdown[all]==0.1.3\n");
});

it('generates requirements.txt with single extra', function (): void {
    Config::set('markitdown.package_extras', 'pdf');
    Config::set('markitdown.package_version', '0.1.3');

    $requirementsPath = $this->tempDir.'/python/requirements.txt';

    $installCommand = new Innobrain\Markitdown\Commands\InstallCommand;
    $reflection = new ReflectionClass($installCommand);

    // Mock the components property
    $reflectionProperty = $reflection->getProperty('components');
    $reflectionProperty->setValue($installCommand, new class
    {
        public function error($message): void {}

        public function info($message): void {}
    });

    $reflectionMethod = $reflection->getMethod('generateRequirements');

    $result = $reflectionMethod->invoke($installCommand, $this->tempDir.'/python');

    expect($result)->toBeTrue();
    expect(File::exists($requirementsPath))->toBeTrue();
    expect(File::get($requirementsPath))->toBe("markitdown[pdf]==0.1.3\n");
});

it('generates requirements.txt with multiple extras', function (): void {
    Config::set('markitdown.package_extras', 'pdf,docx,xlsx');
    Config::set('markitdown.package_version', '0.1.3');

    $requirementsPath = $this->tempDir.'/python/requirements.txt';

    $installCommand = new Innobrain\Markitdown\Commands\InstallCommand;
    $reflection = new ReflectionClass($installCommand);

    // Mock the components property
    $reflectionProperty = $reflection->getProperty('components');
    $reflectionProperty->setValue($installCommand, new class
    {
        public function error($message): void {}

        public function info($message): void {}
    });

    $reflectionMethod = $reflection->getMethod('generateRequirements');

    $result = $reflectionMethod->invoke($installCommand, $this->tempDir.'/python');

    expect($result)->toBeTrue();
    expect(File::exists($requirementsPath))->toBeTrue();
    expect(File::get($requirementsPath))->toBe("markitdown[pdf,docx,xlsx]==0.1.3\n");
});

it('generates requirements.txt with base package only when extras is empty string', function (): void {
    Config::set('markitdown.package_extras', '');
    Config::set('markitdown.package_version', '0.1.3');

    $requirementsPath = $this->tempDir.'/python/requirements.txt';

    $installCommand = new Innobrain\Markitdown\Commands\InstallCommand;
    $reflection = new ReflectionClass($installCommand);

    // Mock the components property
    $reflectionProperty = $reflection->getProperty('components');
    $reflectionProperty->setValue($installCommand, new class
    {
        public function error($message): void {}

        public function info($message): void {}
    });

    $reflectionMethod = $reflection->getMethod('generateRequirements');

    $result = $reflectionMethod->invoke($installCommand, $this->tempDir.'/python');

    expect($result)->toBeTrue();
    expect(File::exists($requirementsPath))->toBeTrue();
    expect(File::get($requirementsPath))->toBe("markitdown==0.1.3\n");
});

it('uses correct version from config', function (): void {
    Config::set('markitdown.package_extras', 'all');
    Config::set('markitdown.package_version', '0.2.0');

    $requirementsPath = $this->tempDir.'/python/requirements.txt';

    $installCommand = new Innobrain\Markitdown\Commands\InstallCommand;
    $reflection = new ReflectionClass($installCommand);

    // Mock the components property
    $reflectionProperty = $reflection->getProperty('components');
    $reflectionProperty->setValue($installCommand, new class
    {
        public function error($message): void {}

        public function info($message): void {}
    });

    $reflectionMethod = $reflection->getMethod('generateRequirements');

    $result = $reflectionMethod->invoke($installCommand, $this->tempDir.'/python');

    expect($result)->toBeTrue();
    expect(File::exists($requirementsPath))->toBeTrue();
    expect(File::get($requirementsPath))->toBe("markitdown[all]==0.2.0\n");
});
