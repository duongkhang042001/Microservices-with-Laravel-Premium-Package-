<?php

namespace StockPickr\Common\Containers\Charts;

use StockPickr\Common\Containers\Container;

final class ChartsUpsertFailedContainer extends Container
{
    public string $ticker;     
    public string $message;

    public static function create(string $ticker, string $message): static
    {
        return static::from(compact('ticker', 'message'));
    }

    public static function from(array $data): static
    {
        $container = new static();
        $container->ticker = $data['ticker'];
        $container->message = $data['message'];

        return $container;
    }
}