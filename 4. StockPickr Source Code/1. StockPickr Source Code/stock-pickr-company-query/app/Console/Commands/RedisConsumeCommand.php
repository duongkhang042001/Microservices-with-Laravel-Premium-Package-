<?php

namespace App\Console\Commands;

use App\Events\CompanyScored;
use App\Events\CompanyUpserted;
use App\Events\RemoveCompany;
use App\Events\ScoreSucceeded;
use App\Services\RedisService;
use Illuminate\Console\Command;
use InvalidArgumentException;
use StockPickr\Common\Containers\CompanyUpsertedContainer;
use StockPickr\Common\Containers\Score\CompanyScoredContainer;
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
                logger('With data: ' . substr(json_encode($event), 0, 100));

                match ($event['type']) {
                    Events::COMPANY_CREATED         => event(new CompanyUpserted(CompanyUpsertedContainer::from($event['data']))),
                    Events::COMPANY_UPDATED         => event(new CompanyUpserted(CompanyUpsertedContainer::from($event['data']))),
                    Events::COMPANY_SCORED          => event(new CompanyScored(CompanyScoredContainer::fromJsonArray($event['data']))),
                    Events::COMPANY_SCORE_SUCCEEDED => event(new ScoreSucceeded()),
                    Events::REMOVE_COMPANY          => event(new RemoveCompany($event['ticker'])),
                    default                         => throw new InvalidArgumentException()
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
