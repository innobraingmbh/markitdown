<?php

declare(strict_types=1);

use function Pest\Laravel\artisan;

it('works', function () {
    $out = artisan('markitdown:convert', [
        'filename' => __DIR__.'/../Stubs/Take Notes.docx',
    ]);

    $out->assertSuccessful();
});

it('doesn\'t work with missing filename', function () {
    $out = artisan('markitdown:convert');

    $out->assertFailed();
})->throws(RuntimeException::class, 'Not enough arguments (missing: "filename").');
