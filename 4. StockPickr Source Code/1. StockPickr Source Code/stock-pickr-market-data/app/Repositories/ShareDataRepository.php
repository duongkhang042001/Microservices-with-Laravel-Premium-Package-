<?php

namespace App\Repositories;

use App\Models\ShareData;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use StockPickr\Common\Containers\ShareDataContainer;

class ShareDataRepository
{
    public function upsert(ShareData $shareData, ShareDataContainer $newShareData): ShareData
    {
        $shareData->price = $newShareData->price;
        $shareData->market_cap = $newShareData->marketCap;
        $shareData->shares_outstanding = $newShareData->sharesOutstanding;
        $shareData->beta = $newShareData->beta;

        $shareData->save();
        return $shareData;
    }

    public function validate(ShareDataContainer $data): void
    {
        $validator = Validator::make($data->toArray(), [
            'price'     => ['required', 'numeric', Rule::notIn([0, '0', ''])],
            'marketCap' => ['required', 'numeric', Rule::notIn([0, '0', ''])],
        ]);

        $validator->validate();
    }

    public function getByTickerOrNew(string $ticker): ShareData
    {
        return ShareData::firstOrNew([
            'ticker'    => $ticker
        ]);
    }

    public function getByTickerOrFail(string $ticker): ShareData
    {
        return ShareData::where('ticker', $ticker)
            ->firstOrFail();
    }

    public function deleteByTicker(string $ticker): void
    {
        ShareData::where('ticker', $ticker)
            ->delete();
    }
}
