<?php

namespace App\Listeners\Score;

use App\Events\Score\ScoreCompaniesSucceeded;
use App\Repositories\CompanyScheduleRepository;

class ScoreCompaniesSucceededListener
{
    public function __construct(
        private CompanyScheduleRepository $companySchedules
    ) {}

    public function handle(ScoreCompaniesSucceeded $event): void
    {
        $this->companySchedules->scoreCompaniesSucceeded($event->scheduleId);
    }
}
