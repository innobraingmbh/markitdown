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
        $processResult = Process::timeout($this->timeout)
            ->command(['markitdown', $filename])
            ->run();

        if (! $processResult->successful()) {
            throw MarkitdownException::processFailed('markitdown', $processResult->output());
        }

        return $processResult->output();
    }

    public function convertString(string $content): string
    {
        $processResult = Process::timeout($this->timeout)
            ->command(['markitdown'])
            ->input($content)
            ->run();

        if (! $processResult->successful()) {
            throw MarkitdownException::processFailed('markitdown', $processResult->output());
        }

        return $processResult->output();
    }
}
