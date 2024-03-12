<?php

namespace StockPickr\Common\Events\Company;

use StockPickr\Common\Containers\Company\CompanyLogContainer;
use StockPickr\Common\Events\Event;
use StockPickr\Common\Events\Events;

class CompanyLogCreatedEvent extends Event
{
    public string $type = Events::COMPANY_LOG_CREATED;

    public function __construct(public CompanyLogContainer $data) {}
}