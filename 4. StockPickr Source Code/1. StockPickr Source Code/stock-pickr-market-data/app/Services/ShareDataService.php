<?php

namespace App\Services;

use App\Models\ShareData;
use App\Repositories\ShareDataRepository;
use StockPickr\Common\Containers\ShareDataContainer;

class ShareDataService
{
    public function __construct(private ShareDataRepository $shareData)
    {
    }

    public function upsert(string $ticker, ShareDataContainer $data): ShareData
    {
        $this->shareData->validate($data);
        $shareData = $this->shareData->getByTickerOrNew($ticker);

        return $this->shareData->upsert($shareData, $data);
    }

    public function delete(string $ticker): void
    {
        $this->shareData->deleteByTicker($ticker);
    }
}
