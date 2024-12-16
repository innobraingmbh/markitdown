<?php

declare(strict_types=1);

namespace Innobrain\Markitdown\Exceptions;

use Exception;

class MarkitdownException extends Exception
{
    public static function processFailed(string $command, string $output, $previous = null): self
    {
        return new self("The command `{$command}` failed with output: {$output}", previous: $previous);
    }
}
