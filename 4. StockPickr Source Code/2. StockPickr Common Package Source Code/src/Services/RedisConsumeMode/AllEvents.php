<?php

namespace StockPickr\Common\Services\RedisConsumeMode;

use Carbon\Carbon;
use Illuminate\Support\Collection;

final class AllEvents extends RedisConsumer
{
    public function getLastEvents(): Collection
    {
        $fromTimestamp = (string) Carbon::now()->subYears(10)->valueOf();

        $allEvents = $this->getEventsAfter($fromTimestamp);
        $parsedEvents = $this->parseEvents($allEvents);

        $unprocessedEvents = $this->rejectProcessedEvents($parsedEvents)->all();

        return $this->rejectFailedEvents($unprocessedEvents);
    }
}