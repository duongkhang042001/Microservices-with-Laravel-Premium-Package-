<?php

namespace StockPickr\Common\Events\Metrics;

use StockPickr\Common\Containers\Metrics\MetricsUpsertedContainer;
use StockPickr\Common\Events\Event;
use StockPickr\Common\Events\Events;

final class MetricsCreatedEvent extends Event
{
    public string $type = Events::METRICS_CREATED;

    public function __construct(public MetricsUpsertedContainer $data) 
    {
    }
}