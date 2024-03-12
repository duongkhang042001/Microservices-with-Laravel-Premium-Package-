<?php

namespace App\Console\Commands;

use App\Repositories\CompanyScheduleRepository;
use App\Services\CompanyProviderService;
use App\Services\RedisService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateCompanyCommand extends Command
{
    protected $signature = 'company:create {ticker?}';
    protected $description = 'Creates a company';

    public function handle(CompanyProviderService $companyProviderService, CompanyScheduleRepository $companySchedules, RedisService $redis): void
    {
        $ticker = $this->argument('ticker');

        if ($ticker) {
            $company = $companyProviderService->getCompany($ticker);
            $alternativeTicker = $company->ticker;
        } else {
            $data = $companyProviderService->getNextCompanyForCreate();
            $company = $data['company'];
            $alternativeTicker = $data['alternativeTicker'];
        }

        $marketData = $companyProviderService->getMarketData($company->ticker, $alternativeTicker);

        DB::transaction(function () use ($company, $marketData, $companySchedules, $redis) {
            $this->logPublishing($company->ticker);

            $companySchedule = $companySchedules->createForCompanyCreate($company);
            $marketDataSchedule = $companySchedules->createForMarketDataCreate($company->ticker, $marketData);

            $redis->publishCreateCompany($company, $companySchedule->id);
            $redis->publishCreateMarketData($company->ticker, $marketData, $marketDataSchedule->id);
        });
    }

    private function logPublishing(string $ticker): void
    {
        logger(sprintf('--------- Publishing create-company for %s --------', $ticker));
    }
}
