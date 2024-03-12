<?php

namespace StockPickr\Common\Containers;

use Illuminate\Support\Arr;
use StockPickr\Common\SafeTypes\NullableInt;

final class RatingContainer extends Container
{
    public ?int $buy;
    public ?int $hold;
    public ?int $sell;
    public ?string $date;

    public static function create(?int $buy, ?int $hold, ?int $sell, ?string $date): static
    {
        return static::from(compact('buy', 'hold', 'sell', 'date'));
    }

    public static function from(array $data): static
    {
        /**
         * Itt elfogadott a 0 érték, de külső forrásból jön, tehát az üres
         * stringet is kezelni kell
         */

        $container = new static();
        $container->buy = NullableInt::create()->format(Arr::get($data, 'buy'));
        $container->hold = NullableInt::create()->format(Arr::get($data, 'hold'));
        $container->sell = NullableInt::create()->format(Arr::get($data, 'sell'));
        $container->date = Arr::get($data, 'date');

        return $container;
    }

    public function getBuy(): ?int
    {
        return $this->buy;
    }

    public function getHold(): ?int
    {
        return $this->hold;
    }

    public function getSell(): ?int
    {
        return $this->sell;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }
}