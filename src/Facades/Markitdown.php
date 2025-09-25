<?php

declare(strict_types=1);

namespace Innobrain\Markitdown\Facades;

use Illuminate\Support\Facades\Facade;
use Override;

/**
 * @see \Innobrain\Markitdown\Markitdown
 *
 * @method static string convert(string $filePath)
 * @method static string convertFile(string $content, string $extension)
 */
class Markitdown extends Facade
{
    #[Override]
    protected static function getFacadeAccessor(): string
    {
        return \Innobrain\Markitdown\Markitdown::class;
    }
}
