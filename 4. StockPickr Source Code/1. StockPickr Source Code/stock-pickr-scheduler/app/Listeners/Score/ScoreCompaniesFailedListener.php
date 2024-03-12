<?php

namespace App\Listeners\Score;

use App\Events\Score\ScoreCompaniesFailed;
use App\Repositories\CompanyScheduleRepository;

class ScoreCompaniesFailedListener
{
    public function __construct(
        private CompanyScheduleRepository $companySchedules
    ) {}

    public function handle(ScoreCompaniesFailed $event): void
    {
        $this->companySchedules->scoreCompaniesFailed($event->scheduleId, $event->data->message);
    }
}
