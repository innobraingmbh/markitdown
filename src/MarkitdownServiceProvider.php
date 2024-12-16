<?php

declare(strict_types=1);

namespace Innobrain\Markitdown;

use Innobrain\Markitdown\Commands\MarkitdownCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MarkitdownServiceProvider extends PackageServiceProvider
{
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
}
