<?php

namespace App\Checkers;

use App\Services\SchedulerService;
use PragmaRX\Health\Checkers\Base;

class StuckedScheduleChecker extends Base
{
    public function check()
    {
        /** @var SchedulerService $scheduler */
        $scheduler = resolve(SchedulerService::class);
        $stuckedSchedules = $scheduler->getStuckedSchedules();
        $count = $stuckedSchedules->count();

        return $this->makeResult(
            $count === 0,
            $count === 0 ? '' : "There are $count schedules are in-progress for a while"
        );
    }
}
