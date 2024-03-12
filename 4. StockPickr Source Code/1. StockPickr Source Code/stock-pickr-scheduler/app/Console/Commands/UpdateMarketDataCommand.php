<?php

namespace App\Console\Commands;

use App\Repositories\CompanyScheduleRepository;
use App\Services\CompanyProviderService;
use App\Services\RedisService;
use Illuminate\Console\Command;
use StockPickr\Common\Containers\MarketData\MarketDataContainer;

class UpdateMarketDataCommand extends Command
{
    protected $signature = 'company:update:market-data';
    protected $description = 'Update market data of a company';

    public function handle(CompanyProviderService $companyProviderService, CompanyScheduleRepository $companySchedules, RedisService $redis): void
    {
        $ticker = $companyProviderService->getNextTickerForShareDataUpdate();
        $shareData = $companyProviderService->getShareData($ticker);
        $marketData = MarketDataContainer::from([
            'shareData' => $shareData->toArray(),
            'analyst'   => []
        ]);

        $this->logPublishing($ticker);

        $schedule = $companySchedules->createForMarketDataUpdate($ticker, $marketData);
        $redis->publishUpdateMarketData($ticker, $marketData, $schedule->id);
    }

    private function logPublishing(string $ticker): void
    {
        logger(sprintf('--------- Publishing update-market-data for %s --------', $ticker));
    }
}
