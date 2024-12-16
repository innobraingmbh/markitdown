<?php

declare(strict_types=1);

namespace Innobrain\Markitdown;

use Illuminate\Process\PendingProcess;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Process;
use Innobrain\Markitdown\Exceptions\MarkitdownException;

class Markitdown
{
    private int $timeout = 30;

    private string $executable = 'markitdown';

    public function __construct()
    {
        $this->timeout = Config::integer('markitdown.process_timeout');
        $this->executable = Config::string('markitdown.executable');
    }

    /**
     * @throws MarkitdownException
     */
    public function convert(string $filename): string
    {
        $processResult = $this->buildProcess()
            ->command([$this->executable, $filename])
            ->run();

        if (! $processResult->successful()) {
            throw MarkitdownException::processFailed($this->executable, $processResult->errorOutput());
        }

        return $processResult->output();
    }

    public function convertString(string $content): string
    {
        $processResult = $this->buildProcess()
            ->command($this->executable)
            ->input($content)
            ->run();

        if (! $processResult->successful()) {
            throw MarkitdownException::processFailed($this->executable, $processResult->errorOutput());
        }

        return $processResult->output();
    }

    private function buildProcess(): PendingProcess
    {
        return Process::timeout($this->timeout)
            ->env([
                'PATH' => getenv('PATH'),
                'HOME' => getenv('HOME'),
            ])
            ->tty(false);
    }
}
