<?php

namespace App\Repositories;

use App\Models\Analyst;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use StockPickr\Common\Containers\AnalystContainer;

class AnalystRepository
{
    public function upsert(Analyst $analyst, AnalystContainer $newAnalyst): Analyst
    {
        $analyst->price_target_low      = $newAnalyst->getPriceTargetLow();
        $analyst->price_target_average  = $newAnalyst->getPriceTargetAverage();
        $analyst->price_target_high     = $newAnalyst->getPriceTargetHigh();

        $analyst->buy                   = $newAnalyst->getBuy();
        $analyst->hold                  = $newAnalyst->getHold();
        $analyst->sell                  = $newAnalyst->getSell();

        $analyst->number_of_analysts    = $newAnalyst->getBuy() + $newAnalyst->getHold() + $newAnalyst->getSell();
        $analyst->rating_date           = $newAnalyst->getRatingDate() ?: now();

        $analyst->save();
        return $analyst;
    }

    public function validate(AnalystContainer $data): void
    {
        if ($data->getBuy() == 0 && $data->getHold() == 0 && $data->getSell() == 0) {
            throw ValidationException::withMessages(['ratings' => ['At least some rating is required']]);
        }

        $validationArray = [
            'priceTargetAverage'    => $data->getPriceTargetAverage(),
            'buy'                   => $data->getBuy(),
            'hold'                  => $data->getHold(),
            'sell'                  => $data->getSell()
        ];
        $validator = Validator::make($validationArray, [
            'priceTargetAverage'    => ['required', 'numeric', Rule::notIn([0, '0', ''])],
            'buy'                   => ['required', 'numeric', Rule::notIn([''])],
            'hold'                  => ['required', 'numeric', Rule::notIn([''])],
            'sell'                  => ['required', 'numeric', Rule::notIn([''])],
        ]);

        $validator->validate();
    }

    public function getByTickerOrNew(string $ticker): Analyst
    {
        return Analyst::firstOrNew([
            'ticker'    => $ticker
        ]);
    }

    public function getByTickerOrFail(string $ticker): Analyst
    {
        return Analyst::where('ticker', $ticker)
            ->firstOrFail();
    }

    public function deleteByTicker(string $ticker): void
    {
        Analyst::where('ticker', $ticker)
            ->delete();
    }
}
