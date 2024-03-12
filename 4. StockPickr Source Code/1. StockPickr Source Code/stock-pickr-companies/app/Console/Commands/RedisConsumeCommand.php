<?php

namespace App\Console\Commands;

use App\Events\Company\RemoveCompany;
use App\Events\Company\UpsertCompany;
use App\Services\RedisService;
use Illuminate\Console\Command;
use InvalidArgumentException;
use StockPickr\Common\Containers\UpsertCompanyContainer;
use StockPickr\Common\Events\Events;
use Throwable;

class RedisConsumeCommand extends Command
{
    protected $signature = 'redis:consume';
    protected $description = 'Subscribe to Redis';

    public function handle(RedisService $redis)
    {
        foreach ($redis->getLastEvents() as $event) {
            try {
                logger('Picking up ' . $event['type']);
                logger('With data: ' . substr(json_encode($event), 0, 100));

                match ($event['type']) {
                    Events::CREATE_COMPANY  => event(new UpsertCompany(UpsertCompanyContainer::from($event['data']), false, $event['scheduleId'])),
                    Events::UPDATE_COMPANY  => event(new UpsertCompany(UpsertCompanyContainer::from($event['data']), true, $event['scheduleId'])),
                    Events::REMOVE_COMPANY  => event(new RemoveCompany($event['ticker'])),
                    default                 => throw new InvalidArgumentException()
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
