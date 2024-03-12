<?php

namespace StockPickr\Common\Events\Company;

use StockPickr\Common\Events\Event;
use StockPickr\Common\Events\Events;

final class RemoveCompanyEvent extends Event
{
    public string $type = Events::REMOVE_COMPANY;

    public function __construct(public string $ticker)
    {
    }
}