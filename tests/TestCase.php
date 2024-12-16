<?php

declare(strict_types=1);

namespace Innobrain\Markitdown\Tests;

use Illuminate\Support\Facades\Config;
use Innobrain\Markitdown\MarkitdownServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Override;

class TestCase extends Orchestra
{
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();
    }

    #[Override]
    protected function getPackageProviders($app)
    {
        return [
            MarkitdownServiceProvider::class,
        ];
    }

    #[Override]
    public function getEnvironmentSetUp($app)
    {
        Config::set('markitdown.process_timeout', 30);
    }
}
