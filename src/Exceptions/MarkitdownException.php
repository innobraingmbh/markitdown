<?php

declare(strict_types=1);

namespace Innobrain\Markitdown\Exceptions;

use Exception;
use Throwable;

class MarkitdownException extends Exception
{
    public static function processFailed(string $command, string $output, ?Throwable $throwable = null): self
    {
        return new self(sprintf('The command `%s` failed with output: %s', $command, $output), previous: $throwable);
    }
}
