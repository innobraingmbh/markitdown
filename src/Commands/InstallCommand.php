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
        $composer = json_decode(file_get_contents($composerPath), true);

        // Add our script to the project's composer.json scripts
        $scriptPath = './vendor/innobrain/markitdown/setup-python-env.sh';
        $scriptAdded = false;

        // Add to post-autoload-dump
        if (! isset($composer['scripts']['post-autoload-dump'])) {
            $composer['scripts']['post-autoload-dump'] = [];
        }

        if (! is_array($composer['scripts']['post-autoload-dump'])) {
            $composer['scripts']['post-autoload-dump'] = [$composer['scripts']['post-autoload-dump']];
        }

        if (! in_array($scriptPath, $composer['scripts']['post-autoload-dump'])) {
            $composer['scripts']['post-autoload-dump'][] = $scriptPath;
            $scriptAdded = true;
        }

        // Add to post-install-cmd
        if (! isset($composer['scripts']['post-install-cmd'])) {
            $composer['scripts']['post-install-cmd'] = [];
        }

        if (! is_array($composer['scripts']['post-install-cmd'])) {
            $composer['scripts']['post-install-cmd'] = [$composer['scripts']['post-install-cmd']];
        }

        if (! in_array($scriptPath, $composer['scripts']['post-install-cmd'])) {
            $composer['scripts']['post-install-cmd'][] = $scriptPath;
            $scriptAdded = true;
        }

        // Add to post-update-cmd
        if (! isset($composer['scripts']['post-update-cmd'])) {
            $composer['scripts']['post-update-cmd'] = [];
        }

        if (! is_array($composer['scripts']['post-update-cmd'])) {
            $composer['scripts']['post-update-cmd'] = [$composer['scripts']['post-update-cmd']];
        }

        if (! in_array($scriptPath, $composer['scripts']['post-update-cmd'])) {
            $composer['scripts']['post-update-cmd'][] = $scriptPath;
            $scriptAdded = true;
        }

        if ($scriptAdded) {
            // Write back to composer.json with proper formatting
            file_put_contents(
                $composerPath,
                str(json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))
                    ->append(PHP_EOL)
                    ->replace(
                        search: "    \"keywords\": [\n        \"laravel\",\n        \"framework\"\n    ],",
                        replace: '    "keywords": ["laravel", "framework"],'
                    )
                    ->toString()
            );

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
        chmod($scriptPath, 0755);

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
