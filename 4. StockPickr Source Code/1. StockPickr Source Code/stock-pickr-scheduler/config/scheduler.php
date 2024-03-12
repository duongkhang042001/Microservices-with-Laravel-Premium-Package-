<?php

return [
    // Ide fog logolni a Console\Kernel. Mivel mindképp file elérést vár /dev/stdout nem működik
    'log_output' => env('SCHEDULER_LOG_OUTPUT')
];
