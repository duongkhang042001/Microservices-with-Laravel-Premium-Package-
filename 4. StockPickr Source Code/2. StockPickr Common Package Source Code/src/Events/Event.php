<?php

namespace StockPickr\Common\Events;

use StockPickr\Common\Support\Arr;

abstract class Event
{
    /**
     * @return array{type: string, id: string, data: array}
     */
    public function toArray(): array
    {
        return Arr::objectToArray($this);        
    }
}