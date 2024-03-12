<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use StockPickr\Common\Containers\CompanyUpsertedContainer;

class CompanyUpserted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public CompanyUpsertedContainer $company,
        public bool $isUpdate
    ) {}
}
