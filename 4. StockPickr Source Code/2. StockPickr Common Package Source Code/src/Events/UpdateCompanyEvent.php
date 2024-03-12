<?php

namespace StockPickr\Common\Events;

use StockPickr\Common\Containers\UpsertCompanyContainer;

class UpdateCompanyEvent extends Event
{
    public string $type = Events::UPDATE_COMPANY;
    public string $scheduleId;
    public UpsertCompanyContainer $data;

    public function __construct(string $scheduleId, UpsertCompanyContainer $data)
    {
        $this->scheduleId = $scheduleId;
        $this->data = $data;
    }
}