<?php

namespace App\Console;

use App\Services\SchedulerService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('cleanup')->everyMinute()->appendOutputTo(config('scheduler.log_output'));

        /** @var SchedulerService $schedulerService */
        $schedulerService = resolve(SchedulerService::class);
        if (!$schedulerService->canStartScheduledCommand()) {
            logger('Cannot start new scheduled commands. Something is still in progress');
            return;
        }

        $schedule->command('company:create')->everyFiveMinutes()->appendOutputTo(config('scheduler.log_output'));
        // $schedule->command('company:update')->everyMinute()->appendOutputTo(config('scheduler.log_output'));
        // $schedule->command('company:update:market-data')->everyMinute()->appendOutputTo(config('scheduler.log_output'));
        // $schedule->command('company:score')->everyMinute()->appendOutputTo(config('scheduler.log_output'));
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
