<?php

namespace StockPickr\Common\Containers\Metrics;

use StockPickr\Common\Containers\Container;

final class MetricsUpsertedContainer extends Container
{
    public string $ticker;
    public MetricsContainer $metrics;
   
    public static function create(
        string $ticker,
        MetricsContainer $metrics,
    ): static {
        $container = new static();
        $container->ticker = $ticker;
        $container->metrics = $metrics;

        return $container;
    }

    public static function from(array $data): static
    {
        $container = new static();
        $container->ticker = $data['ticker'];
        $container->metrics = MetricsContainer::from($data['metrics']);

        return $container;
    }
}