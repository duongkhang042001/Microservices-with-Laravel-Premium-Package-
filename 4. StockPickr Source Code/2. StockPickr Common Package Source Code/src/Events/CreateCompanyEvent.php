<?php

namespace StockPickr\Common\Events;

use StockPickr\Common\Containers\UpsertCompanyContainer;

class CreateCompanyEvent extends Event
{
    public string $type = Events::CREATE_COMPANY;
    public string $scheduleId;
    public UpsertCompanyContainer $data;

    public function __construct(string $scheduleId, UpsertCompanyContainer $data)
    {
        $this->scheduleId = $scheduleId;
        $this->data = $data;
    }
}