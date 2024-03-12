<?php

namespace App\Console\Commands;

use App\Services\SchedulerService;
use Illuminate\Console\Command;

class CleanUpCommand extends Command
{
    protected $signature = 'cleanup';
    protected $description = 'Clean up after stucked schedules';

    public function handle(SchedulerService $schedulerService): void
    {
        logger('-------- Running clean up job --------');
        $schedulerService->cleanUp();
    }
}
