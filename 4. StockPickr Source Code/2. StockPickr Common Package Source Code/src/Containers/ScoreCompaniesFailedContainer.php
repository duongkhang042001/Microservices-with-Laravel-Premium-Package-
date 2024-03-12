<?php

namespace StockPickr\Common\Containers;

final class ScoreCompaniesFailedContainer extends Container
{
    public string $message;

    public static function create(string $message): static
    {
        return static::from(compact('message'));
    }

    public static function from(array $data): static
    {
        $container = new static();
        $container->message = $data['message'];

        return $container;
    }
}