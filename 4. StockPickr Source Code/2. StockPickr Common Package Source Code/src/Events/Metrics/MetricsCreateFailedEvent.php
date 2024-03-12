<?php

namespace StockPickr\Common\Events\Metrics;

use StockPickr\Common\Containers\Metrics\MetricsUpsertFailedContainer;
use StockPickr\Common\Events\Event;
use StockPickr\Common\Events\Events;

class MetricsCreateFailedEvent extends Event
{
    public string $type = Events::METRICS_CREATE_FAILED;

    public function __construct(public MetricsUpsertFailedContainer $data)
    {
    }
}