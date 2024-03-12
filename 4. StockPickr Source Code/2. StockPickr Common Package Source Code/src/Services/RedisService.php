<?php

namespace StockPickr\Common\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redis;
use InvalidArgumentException;
use StockPickr\Common\Events\Event;
use StockPickr\Common\Services\RedisConsumeMode\AllEvents;
use StockPickr\Common\Services\RedisConsumeMode\OnlyNewEvents;
use StockPickr\Common\Services\RedisConsumeMode\RedisConsumer;
use StockPickr\Common\Enums\RedisConsumerModes;

abstract class RedisService
{
    public const GLOBAL_EVENTS_KEY = 'events';
    public const PROCESSED_EVENTS_KEY = 'processed-events';
    public const FAILED_EVENT_KEY = 'failed-events';

    abstract protected function getServiceName(): string;
    abstract protected function getConsumerMode(): string;

    public function getLastEvents(): Collection
    {
        $consumer = $this->createConsumer();
        return $consumer->getLastEvents();
    }

    public function publish(Event $event): void
    {
        /** @phpstan-ignore-next-line */
        Redis::xadd(self::GLOBAL_EVENTS_KEY, '*', [
            'data'          => json_encode($event->toArray()),
            'service'       => $this->getServiceName(),
            'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }

    public function addProcessedEvent(array $event): void
    {
        if ($event['id'] !== null) {
            /** @phpstan-ignore-next-line */
            Redis::zadd($this->getServiceName() . '-' . self::PROCESSED_EVENTS_KEY, $event['id'], $event['id']);
        }
    }

    public function addFailedEvent(array $event): void
    {
        if ($event['id'] !== null) {
            /** @phpstan-ignore-next-line */
            Redis::zadd($this->getServiceName() . '-' . self::FAILED_EVENT_KEY, $event['id'], $event['id']);
        }
    }

    protected function createConsumer(): RedisConsumer
    {
        return match ($this->getConsumerMode()) {
            RedisConsumerModes::ALL_EVENTS => new AllEvents(
                $this->getServiceName(),
                self::GLOBAL_EVENTS_KEY, 
                self::PROCESSED_EVENTS_KEY, 
                self::FAILED_EVENT_KEY
            ),
            RedisConsumerModes::ONLY_NEW_EVENTS => new OnlyNewEvents(
                $this->getServiceName(),
                self::GLOBAL_EVENTS_KEY,
                self::PROCESSED_EVENTS_KEY,
                self::FAILED_EVENT_KEY
            ),
            default => throw new InvalidArgumentException(
                'No Redis consumer mode found for ' . $this->getConsumerMode())
        };
    }
}
