<?php

namespace StockPickr\Common\Events;

use StockPickr\Common\Containers\CompanyUpsertedContainer;

class CompanyCreatedEvent extends Event
{
    public string $type = Events::COMPANY_CREATED;
    public string $scheduleId;
    public CompanyUpsertedContainer $data;

    public function __construct(string $scheduleId, CompanyUpsertedContainer $data)
    {
        $this->scheduleId = $scheduleId;
        $this->data = $data;   
    }
}