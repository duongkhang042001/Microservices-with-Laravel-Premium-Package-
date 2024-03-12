<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use StockPickr\Common\Formatters\Money;
use StockPickr\Common\SafeTypes\NullableFloat;

class AnalystResource extends JsonResource
{
    /**
     * @return array{
     *   priceTarget: array{
     *     low: array{raw: ?float, formatted: ?string},
     *     average: array{raw: float, formatted: ?string},
     *     high: array{raw: ?float, formatted: ?string},
     *   },
     *   rating: array{
     *     buy: int, hold: int, sell: int, date: string
     *   },
     *   numberOfAnalysts: int
     * }
     */
    public function toArray($request)
    {
        return [
            'priceTarget'   => [
                'low' => [
                    'raw'       => NullableFloat::create()->format($this->price_target_low),
                    'formatted' => Money::create()->format($this->price_target_low),
                ],
                'average' => [
                    'raw'       => (float)$this->price_target_average,
                    'formatted' => Money::create()->format($this->price_target_average),
                ],
                'high' => [
                    'raw'       => NullableFloat::create()->format($this->price_target_high),
                    'formatted' => Money::create()->format($this->price_target_high),
                ]
            ],
            'rating' => [
                'buy'   => (int)$this->buy,
                'hold'  => (int)$this->hold,
                'sell'  => (int)$this->sell,
                'date'  => Carbon::parse($this->rating_date)->format('Y-m-d'),
            ],
            'numberOfAnalysts' => (int)$this->number_of_analysts
        ];
    }
}
