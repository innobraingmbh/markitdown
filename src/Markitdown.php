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

    private readonly string $path;

    private readonly string $temporaryDirectory;

    /**
     * @throws MarkitdownException
     */
    public function __construct()
    {
        $this->timeout = Config::integer('markitdown.process_timeout');

        $this->executable = $this->getPathToExecutable();

        $this->path = $this->getPath();

        $this->temporaryDirectory = Config::string('markitdown.temporary_directory');
    }

    /**
     * @param  string  $filePath  Path to the file to be converted. Needs to be readable by the web server.
     * @return string The converted content in markdown
     *
     * @throws MarkitdownException
     */
    public function convert(string $filePath): string
    {
        $processResult = $this->buildProcess()
            ->command([$this->executable, $filePath])
            ->run();

        throw_unless($processResult->successful(), MarkitdownException::processFailed($this->executable, $processResult->errorOutput()));

        return $processResult->output();
    }

    /**
     * @param  string  $content  The content of the file to be converted
     * @param  string  $extension  The extension of the file to be converted, including the dot (e.g. '.docx')
     * @return string The converted content in markdown
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

    private function getPathToExecutable(): string
    {
        if (Config::boolean('markitdown.use_venv_package')) {
            $path = realpath(__DIR__.'/../python/venv/bin/markitdown');

            throw_if($path === false, MarkitdownException::class, 'The path to the python script is invalid');

            return $path;
        }

        return Config::string('markitdown.executable');
    }

    private function getPath(): string
    {
        $path = Config::string('markitdown.system.path');
        if ($path === '') {
            /* Note that this fallback will only work in your console.
             * When running in a web server, you should set MARKITDOWN_SYSTEM_PATH
             * with a place where the markitdown executable is located.
             */
            return getenv('PATH') ?: '/';
        }

        return $path;
    }
}
