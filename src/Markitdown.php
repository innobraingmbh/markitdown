<?php

declare(strict_types=1);

namespace Innobrain\Markitdown;

use Illuminate\Process\PendingProcess;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;
use Innobrain\Markitdown\Exceptions\MarkitdownException;
use Spatie\TemporaryDirectory\Exceptions\PathAlreadyExists;
use Spatie\TemporaryDirectory\TemporaryDirectory;

class Markitdown
{
    private readonly int $timeout;

    private readonly string $executable;

    private string $path;

    private readonly string $temporaryDirectory;

    public function __construct()
    {
        $this->timeout = Config::integer('markitdown.process_timeout');
        $this->executable = Config::string('markitdown.executable');
        $this->path = Config::string('markitdown.system.path');
        $this->temporaryDirectory = Config::string('markitdown.temporary_directory');

        if ($this->path === '') {
            /* Note that this fallback will only work in your console.
             * When running in a web server, you should set MARKITDOWN_SYSTEM_PATH
             * with a place where the markitdown executable is located.
             */
            $this->path = getenv('PATH') ?: '/';
        }
    }

    /**
     * @throws MarkitdownException
     */
    public function convert(string $filePath): string
    {
        $processResult = $this->buildProcess()
            ->command([$this->executable, $filePath])
            ->run();

        if (! $processResult->successful()) {
            throw MarkitdownException::processFailed($this->executable, $processResult->errorOutput());
        }

        return $processResult->output();
    }

    /**
     * @param  string  $content  The content of the file to be converted
     * @param  string  $extension  The extension of the file to be converted, including the dot (e.g. '.docx')
     *
     * @throws MarkitdownException
     * @throws PathAlreadyExists
     */
    public function convertFile(string $content, string $extension): string
    {
        $temporaryDirectory = (new TemporaryDirectory($this->temporaryDirectory))
            ->deleteWhenDestroyed()
            ->force()
            ->create();

        $tempPath = $temporaryDirectory
            ->path(Str::random(40).$extension);

        try {
            file_put_contents($tempPath, $content);

            return $this->convert($tempPath);
        } finally {
            $temporaryDirectory->delete();
        }
    }

    private function buildProcess(): PendingProcess
    {
        return Process::timeout($this->timeout)
            ->env([
                'PATH' => $this->path,
            ])
            ->tty(false);
    }
}
