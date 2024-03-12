<?php

namespace App\Services;

use App\Repositories\CompanyScheduleRepository;
use Illuminate\Support\Collection;

class SchedulerService
{
    private const STUCKED_TRESHOLD_IN_MINUTES = 30;

    public function __construct(private CompanyScheduleRepository $companySchedules)
    {
    }

    public function canStartScheduledCommand(): bool
    {
        return $this->companySchedules->hasInProgressSchedule();
    }

    public function cleanUp(): void
    {
        $stuckSchedules = $this->getStuckedSchedules();
        logger(sprintf('Found %s stucked schedules', $stuckSchedules->count()));

        foreach ($stuckSchedules as $schedule) {
            $this->companySchedules->finish($schedule, now(), CompanyScheduleRepository::STATE_FAILED, 'Stucked schedule. Set failed by Scheduler');
        }
    }

    /**
     * @return Collection<\App\Models\CompanySchedule>
     */
    public function getStuckedSchedules(): Collection
    {
        return $this->companySchedules->getStuckedSchedules(now()->subMinutes(self::STUCKED_TRESHOLD_IN_MINUTES));
    }
}
