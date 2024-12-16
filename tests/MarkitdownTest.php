<?php

declare(strict_types=1);

use Innobrain\Markitdown\Exceptions\MarkitdownException;
use Innobrain\Markitdown\Facades\Markitdown;

it('can execute markitdown', function (): void {
    $conv = Markitdown::convert(__DIR__.'/Stubs/Take Notes.docx');

    expect($conv)->toMatchSnapshot();
});

it('can convert excel', function (): void {
    $conv = Markitdown::convert(__DIR__.'/Stubs/Make a List1.xlsx');

    expect($conv)->toMatchSnapshot();
});

it('can convert from string', function (): void {
    $conv = Markitdown::convertFile(file_get_contents(__DIR__.'/Stubs/Take Notes.docx'), '.docx');

    expect($conv)->toMatchSnapshot();
});

it('throws exception with error output when process fails', function (): void {
    Markitdown::convert('non_existent_file.docx');
})->throws(MarkitdownException::class, 'The command `markitdown` failed with output: Traceback (most recent call last):');
