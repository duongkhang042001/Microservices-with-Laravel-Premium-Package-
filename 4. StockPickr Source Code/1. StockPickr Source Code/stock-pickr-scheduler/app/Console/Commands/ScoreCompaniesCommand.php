<?php

namespace App\Console\Commands;

use App\Repositories\CompanyScheduleRepository;
use App\Services\RedisService;
use Illuminate\Console\Command;

class ScoreCompaniesCommand extends Command
{
    protected $signature = 'company:score';
    protected $description = 'Score companies';

    public function handle(CompanyScheduleRepository $companySchedules, RedisService $redis): void
    {
        $this->logPublishing();

        $schedule = $companySchedules->createForScoreCompanies();
        $redis->publishScoreCompanies($schedule->id);
    }

    private function logPublishing(): void
    {
        logger('--------- Publishing score-companies --------');
    }
}
