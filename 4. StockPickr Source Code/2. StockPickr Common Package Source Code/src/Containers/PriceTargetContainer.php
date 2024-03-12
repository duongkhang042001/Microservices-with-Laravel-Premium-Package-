<?php

namespace StockPickr\Common\Containers;

use Illuminate\Support\Arr;

final class PriceTargetContainer extends Container
{
    public ?float $high;
    public ?float $average;
    public ?float $low;

    public static function create(?float $high, ?float $average, ?float $low): static
    {
        return static::from(compact('high', 'average', 'low'));
    }

    public static function from(array $data): static
    {
        /**
         * Itt nem elfogadott a 0 érték, ezért nem használok NullableFloat -ot Rating -vel ellentétben
         * NullableFloat 0 -ra 0 -t ad vissza, itt viszont 0 -t null -ra kell alakítani
         */

        $container = new static();
        $container->high = Arr::get($data, 'high') ? (float) Arr::get($data, 'high') : null;
        $container->average = Arr::get($data, 'average') ? (float) Arr::get($data, 'average') : null;
        $container->low = Arr::get($data, 'low') ? (float) Arr::get($data, 'low') : null;

        return $container;
    }

    public function getLow(): ?float
    {
        return $this->low;
    }

    public function getAverage(): ?float
    {
        return $this->average;
    }

    public function getHigh(): ?float
    {
        return $this->high;
    }
}