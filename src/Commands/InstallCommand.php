<?php

declare(strict_types=1);

namespace Innobrain\Markitdown\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

class InstallCommand extends Command
{
    public $signature = 'markitdown:install';

    public $description = 'Install Markitdown Python dependencies and set up the virtual environment';

    public function handle(): int
    {
        $this->components->info('Installing Markitdown...');

        // Get the path to the user's composer.json
        $composerPath = $this->getLaravel()->basePath('composer.json');

        if (! file_exists($composerPath)) {
            $this->components->error('composer.json not found in project root.');

            return self::FAILURE;
        }

        // Read composer.json
        $composerJson = file_get_contents($composerPath);

        if ($composerJson === false) {
            $this->components->error('Failed to read composer.json.');

            return self::FAILURE;
        }

        /** @var mixed $composer */
        $composer = json_decode($composerJson, true);

        if (! is_array($composer)) {
            $this->components->error('Invalid composer.json format.');

            return self::FAILURE;
        }

        /** @var array<string, mixed> $composer */

        // Initialize scripts array if it doesn't exist
        if (! isset($composer['scripts'])) {
            $composer['scripts'] = [];
        }

        /** @var array<string, array<string, mixed>> $composer */

        // Add our script to the project's composer.json scripts
        $scriptPath = './vendor/innobrain/markitdown/setup-python-env.sh';
        $scriptAdded = false;

        // Add to post-autoload-dump
        if (! isset($composer['scripts']['post-autoload-dump'])) {
            $composer['scripts']['post-autoload-dump'] = [];
        }

        /** @var array<string, array<int|string, string|array<string>>> $composer */

        // Ensure post-autoload-dump is an array
        if (! is_array($composer['scripts']['post-autoload-dump'])) {
            $composer['scripts']['post-autoload-dump'] = [$composer['scripts']['post-autoload-dump']];
        }

        if (! in_array($scriptPath, $composer['scripts']['post-autoload-dump'], true)) {
            $composer['scripts']['post-autoload-dump'][] = $scriptPath;
            $scriptAdded = true;
        }

        if ($scriptAdded) {
            // Write back to composer.json with proper formatting
            $encodedJson = json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

            if ($encodedJson === false) {
                $this->components->error('Failed to encode composer.json.');

                return self::FAILURE;
            }

            $formattedJson = str($encodedJson)
                ->append(PHP_EOL)
                ->replace(
                    search: "    \"keywords\": [\n        \"laravel\",\n        \"framework\"\n    ],",
                    replace: '    "keywords": ["laravel", "framework"],'
                )
                ->toString();

            if (in_array(file_put_contents($composerPath, $formattedJson), [0, false], true)) {
                $this->components->error('Failed to write to composer.json.');

                return self::FAILURE;
            }

            $this->components->info('Added Markitdown setup script to composer.json');
        }

        // Run the setup script
        $scriptPath = realpath(__DIR__.'/../../setup-python-env.sh');

        if ($scriptPath === false || ! file_exists($scriptPath)) {
            $this->components->error('Setup script not found.');

            return self::FAILURE;
        }

        $this->components->info('Setting up Python virtual environment...');

        // Make the script executable
        if (! chmod($scriptPath, 0755)) {
            $this->components->error('Failed to make setup script executable.');

            return self::FAILURE;
        }

        // Run the setup script
        $pendingProcess = Process::path(dirname($scriptPath))
            ->tty(false)
            ->timeout(300);

        $processResult = $pendingProcess->run($scriptPath);

        if (! $processResult->successful()) {
            $this->components->error('Failed to set up Python virtual environment: '.$processResult->errorOutput());

            return self::FAILURE;
        }

        $this->components->info('Markitdown installed successfully!');

        return self::SUCCESS;
    }
}
