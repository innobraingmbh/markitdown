<?php

declare(strict_types=1);

namespace Innobrain\Markitdown\Tests;

use Illuminate\Support\Facades\Config;
use Innobrain\Markitdown\MarkitdownServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            MarkitdownServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        Config::set('markitdown.process_timeout', 30);
    }
}
