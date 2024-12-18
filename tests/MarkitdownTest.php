<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Config;
use Innobrain\Markitdown\Exceptions\MarkitdownException;
use Innobrain\Markitdown\Facades\Markitdown;

dataset('venv_configs', [
    'with venv enabled' => true,
    'with venv disabled' => false,
]);

it('can execute markitdown with different venv configs', function (bool $useVenv): void {
    Config::set('markitdown.use_venv_package', $useVenv);

    $conv = Markitdown::convert(__DIR__.'/Stubs/Take Notes.docx');

    expect($conv)->toMatchSnapshot();
})->with('venv_configs');

it('can convert excel with different venv configs', function (bool $useVenv): void {
    Config::set('markitdown.use_venv_package', $useVenv);

    $conv = Markitdown::convert(__DIR__.'/Stubs/Make a List1.xlsx');

    expect($conv)->toMatchSnapshot();
})->with('venv_configs');

it('can convert from string with different venv configs', function (bool $useVenv): void {
    Config::set('markitdown.use_venv_package', $useVenv);

    $conv = Markitdown::convertFile(file_get_contents(__DIR__.'/Stubs/Take Notes.docx'), '.docx');

    expect($conv)->toMatchSnapshot();
})->with('venv_configs');

it('throws exception with error output when process fails', function (): void {
    Config::set('markitdown.use_venv_package', false);

    Markitdown::convert('non_existent_file.docx');
})->throws(MarkitdownException::class, 'The command `markitdown` failed with output: Traceback (most recent call last):');
