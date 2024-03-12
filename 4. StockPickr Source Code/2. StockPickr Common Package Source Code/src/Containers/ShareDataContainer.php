<?php

namespace StockPickr\Common\Containers;

use Illuminate\Support\Arr;

final class ShareDataContainer extends Container
{
    public ?float $price;
    public ?float $marketCap;
    public ?float $sharesOutstanding;
    public ?float $beta;

    public static function create(?float $price, ?float $beta, ?float $marketCap, ?float $sharesOutstanding): static
    {
        return static::from(compact('price', 'beta', 'marketCap', 'sharesOutstanding'));
    }

    public static function from(array $data): static
    {
        /**
         * Mivel az érékek külső forrásból jönnek, lehet, hogy üres string érkezik vagy éppen
         * Mindkét érték null -ként értelmezhető (nincs cég 0 árral vagy bétával)
         */
        $container = new static();
        $container->price = Arr::get($data, 'price') ?: null;
        $container->beta = Arr::get($data, 'beta') ?: null;

        $container->marketCap = Arr::get($data, 'marketCap') ?: null;
        $container->sharesOutstanding = Arr::get($data, 'sharesOutstanding') ?: null;

        return $container;
    }
}