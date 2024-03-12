<?php

namespace App\Containers;

use StockPickr\Common\Containers\Container;

final class MetricContainer extends Container
{
    public string $name;
    public string $slug;
    public string $slugCamel;
    public string $formatter;
    public bool $shouldHighlightNegativeValue;
    public string $scoreRule;

    public static function from(array $data): static
    {
        $container = new static();
        $container->name = $data['name'];
        $container->slug = $data['slug'];
        $container->slugCamel = $data['slugCamel'];
        $container->formatter = $data['formatter'];
        $container->shouldHighlightNegativeValue = $data['shouldHighlightNegativeValue'];
        $container->scoreRule = $data['scoreRule'];

        return $container;
    }
}
