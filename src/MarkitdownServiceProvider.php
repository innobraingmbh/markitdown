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
        $scriptPath = realpath(__DIR__.'/../setup-python-env.sh');

        if ($scriptPath === false) {
            $this->writeOutput("\n⚠️  Markitdown setup script not found.");

            return;
        }

        if (! file_exists($scriptPath)) {
            $this->writeOutput("\n⚠️  Markitdown setup script does not exist.");

            return;
        }

        $this->writeOutput("\n🔧 Setting up Markitdown virtual environment...\n");

        // Make the script executable
        chmod($scriptPath, 0755);

        // Run the setup script
        $result = Process::path(dirname($scriptPath))
            ->command($scriptPath)
            ->tty(false)
            ->timeout(300)
            ->run();

        // Output the process output
        $this->writeOutput($result->output());

        if ($result->successful()) {
            $this->writeOutput("\n✅ Markitdown virtual environment setup complete!\n");
        } else {
            $this->writeOutput("\n❌ Markitdown virtual environment setup failed. Error output:\n");
            $this->writeOutput($result->errorOutput());
            $this->writeOutput("\n");
        }
    }

    private function writeOutput(string $message): void
    {
        // Check if we're in a console environment
        if (PHP_SAPI === 'cli') {
            fwrite(STDOUT, $message);
        } else {
            // For non-CLI environments (like web requests), we could log the message
            info('[Markitdown Setup] '.$message);
        }
    }
}
