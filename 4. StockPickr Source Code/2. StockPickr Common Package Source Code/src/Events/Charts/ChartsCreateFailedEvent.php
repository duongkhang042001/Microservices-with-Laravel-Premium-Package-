<?php

namespace StockPickr\Common\Events\Charts;

use StockPickr\Common\Containers\Charts\ChartsUpsertFailedContainer;
use StockPickr\Common\Events\Event;
use StockPickr\Common\Events\Events;

final class ChartsCreateFailedEvent extends Event
{
    public string $type = Events::CHARTS_CREATE_FAILED;

    public function __construct(public ChartsUpsertFailedContainer $data)
    {
    }
}