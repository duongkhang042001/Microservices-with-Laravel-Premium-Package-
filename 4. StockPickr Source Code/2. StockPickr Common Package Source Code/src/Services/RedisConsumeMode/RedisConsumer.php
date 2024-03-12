<?php

namespace StockPickr\Common\Services\RedisConsumeMode;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redis;

abstract class RedisConsumer
{
    abstract public function getLastEvents(): Collection;

    public function __construct(
        protected string $serviceName,
        protected string $allEventsKey,
        protected string $processedEventsKey,
        protected string $failedEventsKey
    ) {}

    public function getEventsAfter(string $start): array
    {
        $end = (int) Carbon::now()->valueOf();
        /** @phpstan-ignore-next-line */
        $events = Redis::xRange($this->allEventsKey, $start, $end);
        unset($events[$start]);

        return $events;
    }

    public function parseEvents(array $eventsFromRedis): array
    {
        $events = [];
        foreach ($eventsFromRedis as $id => $item) {
            $events[] = array_merge(
                json_decode($item['data'], true),
                ['id' => $id]
            );
        }

        return $events;
    }    

    public function rejectFailedEvents(array $parsedEvents): Collection
    {
        /** @phpstan-ignore-next-line */
        $failedEvents = Redis::zrange($this->serviceName . '-' . $this->failedEventsKey, 0, -1);
        return collect($parsedEvents)
            ->reject(fn (array $event) => in_array($event['id'], $failedEvents))
            ->values();
    }

    public function rejectProcessedEvents(array $parsedEvents): Collection
    {
        /** @phpstan-ignore-next-line */
        $processedEvents = Redis::zrange($this->serviceName . '-' . $this->processedEventsKey, 0, -1);
        return collect($parsedEvents)
            ->reject(fn (array $event) => in_array($event['id'], $processedEvents))
            ->values();
    }

    public function getLastProcessedEventId(): string
    {
        /** @phpstan-ignore-next-line */
        $lastEventIdAsArray = Redis::zrevrange($this->serviceName . '-' . $this->processedEventsKey, 0, 0);
        if (!$lastEventIdAsArray || empty($lastEventIdAsArray)) {
            return (string) Carbon::now()->subYears(10)->valueOf();
        }

        return $lastEventIdAsArray[0];
    }
}