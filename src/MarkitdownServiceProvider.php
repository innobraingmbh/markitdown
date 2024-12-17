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
    private static bool $setupRun = false;

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
        if (! self::$setupRun) {
            $this->setupVirtualEnvironment();
            self::$setupRun = true;
        }
    }

    private function setupVirtualEnvironment(): void
    {
        $scriptPath = realpath(__DIR__.'/../setup-python-env.sh');

        if ($scriptPath === false || ! file_exists($scriptPath)) {
            return;
        }

        $this->writeOutput("\n🔧 Setting up Markitdown virtual environment...");

        // Make the script executable
        chmod($scriptPath, 0755);

        // Run the setup script
        $process = Process::path(dirname($scriptPath))
            ->command($scriptPath)
            ->tty(false)
            ->timeout(300);

        $result = $process->run();

        if ($result->successful()) {
            $this->writeOutput("\n✅ Markitdown virtual environment setup complete!\n");
        } else {
            $this->writeOutput("\n❌ Markitdown setup failed. Error: ".$result->errorOutput()."\n");
        }
    }

    private function writeOutput(string $message): void
    {
        if (PHP_SAPI === 'cli') {
            fwrite(STDOUT, $message);
        } else {
            info('[Markitdown Setup] '.$message);
        }
    }
}
