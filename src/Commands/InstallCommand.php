<?php

declare(strict_types=1);

namespace Innobrain\Markitdown\Commands;

use Illuminate\Console\Command;
use Illuminate\Process\Factory;
use Illuminate\Support\Facades\Config;
use Kauffinger\Pyman\Exceptions\PymanException;
use Kauffinger\Pyman\PythonEnvironmentManager;

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
        $script = '@php artisan markitdown:install';
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

        if (! in_array($script, $composer['scripts']['post-autoload-dump'], true)) {
            $composer['scripts']['post-autoload-dump'][] = $script;
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

        $pythonPath = realpath(__DIR__.'/../../python');

        if ($pythonPath === false) {
            $this->components->error('Python virtual environment not found.');

            return self::FAILURE;
        }

        $this->components->info('Installing or updating python environment...');

        // Generate requirements.txt based on configuration
        if (! $this->generateRequirements($pythonPath)) {
            return self::FAILURE;
        }

        $pythonEnvironmentManager = new PythonEnvironmentManager($pythonPath, app(Factory::class));

        try {
            $pythonEnvironmentManager->setup();
        } catch (PymanException $pymanException) {
            $this->components->error($pymanException->getMessage());

            return self::FAILURE;
        }

        $this->components->info('Markitdown installed successfully!');

        return self::SUCCESS;
    }

    /**
     * Generate requirements.txt based on package_extras configuration
     */
    private function generateRequirements(string $pythonPath): bool
    {
        $packageVersion = Config::string('markitdown.package_version', '0.1.3');
        $packageExtras = Config::string('markitdown.package_extras', 'all');
        $requirementsPath = $pythonPath.'/requirements.txt';

        $packageSpec = sprintf('markitdown[%s]==%s', $packageExtras, $packageVersion);

        if ($packageExtras === '') {
            $packageSpec = 'markitdown=='.$packageVersion;
        }

        // Write the requirements file
        $content = $packageSpec."\n";

        if (file_put_contents($requirementsPath, $content) === false) {
            $this->components->error('Failed to write requirements.txt');

            return false;
        }

        $this->components->info('Generated requirements.txt with: '.$packageSpec);

        return true;
    }
}
