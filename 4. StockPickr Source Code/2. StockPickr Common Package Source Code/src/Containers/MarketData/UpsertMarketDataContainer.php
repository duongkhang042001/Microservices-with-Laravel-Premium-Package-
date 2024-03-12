<?php

namespace StockPickr\Common\Containers\MarketData;

use StockPickr\Common\Containers\Container;

final class UpsertMarketDataContainer extends Container
{
    public string $ticker;
    public MarketDataContainer $marketData;

    public static function create(
        string $ticker,
        MarketDataContainer $marketData
    ): static
    {
        return static::from([
            'ticker'        => $ticker,
            'marketData'    => $marketData->toArray()
        ]);
    }

    public static function from(array $data): static
    {
        $container = new static();
        $container->ticker = $data['ticker'];
        $container->marketData = MarketDataContainer::from($data['marketData']);

        return $container;
    }
}