<?php

namespace StockPickr\Common\Containers;

use StockPickr\Common\Support\Arr;

abstract class Container
{
    public function toArray(): array
    {
        return Arr::objectToArray($this);
    }
}