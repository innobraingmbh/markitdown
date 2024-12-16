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
     * the binary will be searched in the PATH.
     */
    'executable' => env('MARKITDOWN_EXECUTABLE', 'markitdown'),
];
