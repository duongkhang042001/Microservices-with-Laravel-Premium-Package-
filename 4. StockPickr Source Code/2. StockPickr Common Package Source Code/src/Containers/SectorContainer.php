<?php

namespace StockPickr\Common\Containers;

final class SectorContainer extends Container
{
    public int $id;
    public string $name;

    public static function create(int $id, string $name): static
    {
        return static::from(compact('id', 'name'));
    }

    public static function from(array $data): static
    {
        $container = new static();
        $container->id = $data['id'];
        $container->name = $data['name'];

        return $container;
    }
}