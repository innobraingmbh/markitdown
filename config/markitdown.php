<?php

declare(strict_types=1);

// config for Innobrain/Markitdown
return [
    /*
     * Use this to set the timeout for the process. Default is 30 seconds.
     */
    'process_timeout' => env('MARKITDOWN_PROCESS_TIMEOUT', 30),

    /*
     * Use this to set the path to the markitdown executable. If not set,
     * the binary will be searched in the PATH. Will be ignored
     * if use_venv_package is set to true.
     */
    'executable' => env('MARKITDOWN_EXECUTABLE', 'markitdown'),

    /*
     * This will override the above setting and use the new locally installed package.
     */
    'use_venv_package' => env('MARKITDOWN_USE_VENV_PACKAGE', true),

    /*
     * This is needed when you want to run markitdown in php-fpm. One dependency
     * of markitdown requires PATH to be set. If you are running in a console,
     * this is not needed.
     */
    'system' => [
        'path' => env('MARKITDOWN_SYSTEM_PATH', ''),
    ],

    /*
     * The path where temporary files will be stored. This directory must be writable
     * by the web server. Defaults to storage/app/private/markitdown_tmp
     */
    'temporary_directory' => env('MARKITDOWN_TEMPORARY_DIRECTORY', storage_path('app/private/markitdown_tmp')),
];
