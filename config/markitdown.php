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

    /*
     * The version of the markitdown Python package to install.
     * This should match the version tested with this package.
     */
    'package_version' => '0.1.3',

    /*
     * Specify which markitdown package extras to install.
     * Available extras: 'all', 'pdf', 'docx', 'pptx', 'xls', 'xlsx',
     * 'audio-transcription', 'youtube-transcription', 'az-doc-intel', 'outlook'
     *
     * Use 'all' to install all available extras (default)
     * Use a string for extras: 'pdf,xslx'
     * Leave empty for base package only (no extras)
     */
    'package_extras' => env('MARKITDOWN_PACKAGE_EXTRAS', 'all'),

    /*
     * Whether to automatically inject the markitdown:install command into
     * the composer.json post-autoload-dump scripts section.
     * Set to false to prevent automatic injection.
     */
    'inject_composer_script' => env('MARKITDOWN_INJECT_COMPOSER_SCRIPT', true),
];
