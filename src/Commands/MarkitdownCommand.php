<?php

declare(strict_types=1);

namespace Innobrain\Markitdown\Commands;

use Illuminate\Console\Command;
use Innobrain\Markitdown\Exceptions\MarkitdownException;
use Innobrain\Markitdown\Facades\Markitdown;

class MarkitdownCommand extends Command
{
    public $signature = 'markitdown:convert {filename}';

    public $description = 'Convert a file to markdown';

    public function handle(): int
    {
        $filename = $this->argument('filename');

        try {
            $output = Markitdown::convert($filename);
            /* @phpstan-ignore catch.neverThrown */
        } catch (MarkitdownException $markitdownException) {
            $this->error($markitdownException->getMessage());

            return self::FAILURE;
        }

        $this->line($output);

        return self::SUCCESS;
    }
}
