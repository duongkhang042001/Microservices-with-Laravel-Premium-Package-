<?php

namespace StockPickr\Common\Services\RedisConsumeMode;

use Illuminate\Support\Collection;

final class OnlyNewEvents extends RedisConsumer
{
    public function getLastEvents(): Collection
    {
        $lastEventId = $this->getLastProcessedEventId();
        
        $unprocessedEvents = $this->getEventsAfter($lastEventId);
        $parsedEvents = $this->parseEvents($unprocessedEvents);

        return $this->rejectFailedEvents($parsedEvents);
    }
}