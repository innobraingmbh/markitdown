<?php

declare(strict_types=1);

namespace Innobrain\Markitdown\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Innobrain\Markitdown\Markitdown
 */
class Markitdown extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Innobrain\Markitdown\Markitdown::class;
    }
}
