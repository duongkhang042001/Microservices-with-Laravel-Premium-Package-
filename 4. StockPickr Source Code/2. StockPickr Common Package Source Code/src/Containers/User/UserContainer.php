<?php

namespace StockPickr\Common\Containers\User;

use StockPickr\Common\Containers\Container;

final class UserContainer extends Container
{
    public string $id;
    public string $email;
    public string $fullName;

    public static function create(string $id, string $email, string $fullName): static
    {
        $container = new static();
        $container->id = $id;
        $container->email = $email;
        $container->fullName = $fullName;

        return $container;
    }
}