<?php

namespace StockPickr\Common\Containers\MarketData;

use StockPickr\Common\Containers\AnalystContainer;
use StockPickr\Common\Containers\Container;
use StockPickr\Common\Containers\ShareDataContainer;

final class MarketDataContainer extends Container
{
    public ShareDataContainer $shareData;
    public AnalystContainer $analyst;

    public static function create(ShareDataContainer $shareData, AnalystContainer $analyst): static
    {
        return static::from([
            'shareData' => $shareData->toArray(),
            'analyst'   => $analyst->toArray()
        ]);
    }

    public static function from(array $data): static
    {
        $container = new static();
        $container->shareData = ShareDataContainer::from($data['shareData']);
        $container->analyst = AnalystContainer::from($data['analyst']);

        return $container;
    }

    public function getShareData(): ShareDataContainer
    {
        return $this->shareData;
    }

    public function getAnalyst(): AnalystContainer
    {
        return $this->analyst;
    }
}