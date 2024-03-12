<?php

namespace App\Console\Commands;

use App\Repositories\CompanyScheduleRepository;
use App\Services\CompanyProviderService;
use App\Services\RedisService;
use Illuminate\Console\Command;

class UpdateCompanyCommand extends Command
{
    protected $signature = 'company:update';
    protected $description = 'Updates a company';

    public function handle(CompanyProviderService $companyProviderService, CompanyScheduleRepository $companySchedules, RedisService $redis): void
    {
        $company = $companyProviderService->getNextCompanyForUpdate();
        $this->logPublishing($company->ticker);

        $schedule = $companySchedules->createForCompanyUpdate($company);
        $redis->publishUpdateCompany($company, $schedule->id);
    }

    private function logPublishing(string $ticker): void
    {
        logger(sprintf('--------- Publishing update-company for %s --------', $ticker));
    }
}
