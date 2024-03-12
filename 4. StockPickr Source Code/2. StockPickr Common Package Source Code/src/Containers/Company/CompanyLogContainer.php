<?php

namespace StockPickr\Common\Containers\Company;

use StockPickr\Common\Containers\Container;

final class CompanyLogContainer extends Container
{
    public string $action;
    public string $payload;

    public static function create(string $action, string $payload): static
    {
        $container = new static();
        $container->action = $action;
        $container->payload = $payload;

        return $container;
    }
}