<?php

declare(strict_types=1);

namespace Innobrain\Markitdown;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Process;
use Innobrain\Markitdown\Exceptions\MarkitdownException;

class Markitdown
{
    private int $timeout = 30;

    public function __construct()
    {
        $this->timeout = Config::integer('markitdown.process_timeout');
    }

    /**
     * @throws MarkitdownException
     */
    public function convert(string $filename): string
    {
        $result = Process::timeout($this->timeout)
            ->command(['markitdown', $filename])
            ->run();

        if (! $result->successful()) {
            throw MarkitdownException::processFailed('markitdown', $result->output());
        }

        return $result->output();
    }
}
