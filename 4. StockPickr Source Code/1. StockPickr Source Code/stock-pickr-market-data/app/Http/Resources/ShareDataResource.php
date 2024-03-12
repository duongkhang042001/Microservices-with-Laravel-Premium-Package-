<?php

namespace App\Http\Resources;

use App\Formatters\MarketCap;
use Illuminate\Http\Resources\Json\JsonResource;
use StockPickr\Common\Formatters\Money;
use StockPickr\Common\SafeTypes\NullableFloat;

class ShareDataResource extends JsonResource
{
    /**
     * @return array{
     *   price: array{raw: float, formatted: ?string},
     *   marketCap: array{raw: float, formatted: ?string},
     *   sharesOutstanding: ?float,
     *   beta: ?float
     * }
     */
    public function toArray($request)
    {
        return [
            'price' => [
                'raw'           => (float)$this->price,
                'formatted'     => Money::create()->format($this->price),
            ],
            'marketCap' => [
                'raw'           => (float)$this->market_cap,
                'formatted'     => MarketCap::create()->format($this->market_cap),
            ],
            'sharesOutstanding' => NullableFloat::create()->format($this->shares_outstanding),
            'beta'              => NullableFloat::create()->format($this->beta),
        ];
    }
}
