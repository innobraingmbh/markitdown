<?php

declare(strict_types=1);

use Innobrain\Markitdown\Facades\Markitdown;

it('can execute markitdown', function () {
    $conv = Markitdown::convert(__DIR__.'/Stubs/Take Notes.docx');

    expect($conv)->toMatchSnapshot();
});

it('can convert excel', function () {
    $conv = Markitdown::convert(__DIR__.'/Stubs/Make a List1.xlsx');

    expect($conv)->toMatchSnapshot();
});
