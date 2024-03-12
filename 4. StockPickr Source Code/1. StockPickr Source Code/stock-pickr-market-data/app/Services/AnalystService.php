<?php

namespace App\Services;

use App\Models\Analyst;
use App\Repositories\AnalystRepository;
use StockPickr\Common\Containers\AnalystContainer;

class AnalystService
{
    public function __construct(private AnalystRepository $analysts)
    {
    }

    public function upsert(string $ticker, AnalystContainer $data): Analyst
    {
        $this->analysts->validate($data);
        $analyst = $this->analysts->getByTickerOrNew($ticker);

        return $this->analysts->upsert($analyst, $data);
    }

    public function delete(string $ticker): void
    {
        $this->analysts->deleteByTicker($ticker);
    }
}
