<?php

namespace StockPickr\Common\Events\MarketData;

use StockPickr\Common\Containers\MarketData\UpsertMarketDataContainer;
use StockPickr\Common\Events\Event;
use StockPickr\Common\Events\Events;

class MarketDataCreatedEvent extends Event
{
    public string $type = Events::MARKET_DATA_CREATED;
    public string $scheduleId;
    public UpsertMarketDataContainer $data;

    public function __construct(string $scheduleId, UpsertMarketDataContainer $data)
    {
        $this->scheduleId = $scheduleId;
        $this->data = $data;
    }
}