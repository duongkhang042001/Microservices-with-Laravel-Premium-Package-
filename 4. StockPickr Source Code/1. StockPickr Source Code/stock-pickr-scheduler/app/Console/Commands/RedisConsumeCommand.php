<?php

namespace App\Console\Commands;

use App\Events\Charts\ChartsCreateFailed;
use App\Events\Company\CompanyUpserted;
use App\Events\Company\CompanyUpsertFailed;
use App\Events\MarketData\MarketDataUpserted;
use App\Events\MarketData\MarketDataUpsertFailed;
use App\Events\Metrics\MetricsCreateFailed;
use App\Events\Score\ScoreCompaniesFailed;
use App\Events\Score\ScoreCompaniesSucceeded;
use App\Services\RedisService;
use Illuminate\Console\Command;
use InvalidArgumentException;
use StockPickr\Common\Containers\Charts\ChartsUpsertFailedContainer;
use StockPickr\Common\Containers\CompanyUpsertedContainer;
use StockPickr\Common\Containers\CompanyUpsertFailedContainer;
use StockPickr\Common\Containers\MarketData\MarketDataUpsertFailedContainer;
use StockPickr\Common\Containers\MarketData\UpsertMarketDataContainer;
use StockPickr\Common\Containers\Metrics\MetricsUpsertFailedContainer;
use StockPickr\Common\Containers\ScoreCompaniesFailedContainer;
use StockPickr\Common\Events\Events;
use Throwable;

class RedisConsumeCommand extends Command
{
    protected $signature = 'redis:consume';
    protected $description = 'Consume Redis stream';

    public function handle(RedisService $redis): void
    {
        foreach ($redis->getLastEvents() as $event) {
            try {
                logger('Picking up ' . $event['type']);
                logger('With data: ' . substr((string) json_encode($event), 0, 200));

                match ((string) $event['type']) {
                    Events::COMPANY_CREATED             => event(new CompanyUpserted(CompanyUpsertedContainer::from($event['data']), false, $event['scheduleId'])),
                    Events::COMPANY_CREATE_FAILED       => event(new CompanyUpsertFailed(CompanyUpsertFailedContainer::from($event['data']), false, $event['scheduleId'])),

                    Events::COMPANY_UPDATED             => event(new CompanyUpserted(CompanyUpsertedContainer::from($event['data']), true, $event['scheduleId'])),
                    Events::COMPANY_UPDATE_FAILED       => event(new CompanyUpsertFailed(CompanyUpsertFailedContainer::from($event['data']), true, $event['scheduleId'])),

                    Events::MARKET_DATA_CREATED         => event(new MarketDataUpserted(UpsertMarketDataContainer::from($event['data']), false, $event['scheduleId'])),
                    Events::MARKET_DATA_CREATE_FAILED   => event(new MarketDataUpsertFailed(MarketDataUpsertFailedContainer::from($event['data']), false, $event['scheduleId'])),

                    Events::MARKET_DATA_UPDATED        => event(new MarketDataUpserted(UpsertMarketDataContainer::from($event['data']), true, $event['scheduleId'])),
                    Events::MARKET_DATA_UPDATE_FAILED  => event(new MarketDataUpsertFailed(MarketDataUpsertFailedContainer::from($event['data']), true, $event['scheduleId'])),

                    Events::COMPANY_SCORE_SUCCEEDED     => event(new ScoreCompaniesSucceeded($event['scheduleId'])),
                    Events::COMPANY_SCORE_FAILED        => event(new ScoreCompaniesFailed(ScoreCompaniesFailedContainer::from($event['data']), $event['scheduleId'])),

                    Events::METRICS_CREATE_FAILED       => event(new MetricsCreateFailed(MetricsUpsertFailedContainer::from($event['data']))),
                    Events::CHARTS_CREATE_FAILED        => event(new ChartsCreateFailed(ChartsUpsertFailedContainer::from($event['data']))),

                    default                             => throw new InvalidArgumentException()
                };

                $redis->addProcessedEvent($event);
            } catch (InvalidArgumentException) {

            } catch (Throwable $ex) {
                $redis->addFailedEvent($event);
                throw $ex;
            }
        }
    }
}
