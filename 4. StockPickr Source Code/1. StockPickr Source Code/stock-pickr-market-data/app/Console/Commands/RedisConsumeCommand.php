<?php

namespace App\Console\Commands;

use App\Events\DeleteCompany;
use App\Events\UpsertMarketData;
use App\Services\RedisService;
use Illuminate\Console\Command;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use StockPickr\Common\Containers\CompanyUpsertFailedContainer;
use StockPickr\Common\Containers\MarketData\UpsertMarketDataContainer;
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
                logger('With data: ' . substr((string)json_encode($event), 0, 100));

                match ($event['type']) {
                    Events::CREATE_MARKET_DATA      => event(new UpsertMarketData(UpsertMarketDataContainer::from($event['data']), false, $event['scheduleId'])),
                    Events::UPDATE_MARKET_DATA      => event(new UpsertMarketData(UpsertMarketDataContainer::from($event['data']), true, $event['scheduleId'])),
                    Events::REMOVE_COMPANY          => event(new DeleteCompany($event['ticker'])),
                    default                         => throw new InvalidArgumentException()
                };

                $redis->addProcessedEvent($event);
            } catch (InvalidArgumentException) {

            } catch (ValidationException $vex) {
                $redis->addFailedEvent($event);
                logger(sprintf('-------- %s failed with validation erros --------', $event['type']));
                logger((string)json_encode($vex->errors()));
            } catch (Throwable $ex) {
                $redis->addFailedEvent($event);
                throw $ex;
            }
        }
    }
}
