<?php

declare(strict_types=1);

namespace Innobrain\Markitdown;

use Illuminate\Support\Facades\Process;
use Innobrain\Markitdown\Commands\MarkitdownCommand;
use Override;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MarkitdownServiceProvider extends PackageServiceProvider
{
    #[Override]
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('markitdown')
            ->hasConfigFile()
            ->hasCommand(MarkitdownCommand::class);
    }

    #[Override]
    public function packageRegistered(): void
    {
        $this->setupVirtualEnvironment();
    }

    private function setupVirtualEnvironment(): void
    {
        $scriptPath = realpath(__DIR__ . '/../setup-python-env.sh');

        if ($scriptPath === false) {
            return;
        }

        if (! file_exists($scriptPath)) {
            return;
        }

        // Make the script executable
        chmod($scriptPath, 0755);

        // Run the setup script
        Process::path(dirname($scriptPath))
            ->run($scriptPath);
    }
}
