<?php

namespace StockPickr\Common\Containers;

use Illuminate\Support\Arr;

final class AnalystContainer extends Container
{
    public PriceTargetContainer $priceTarget;
    public RatingContainer $rating;

    public static function create(PriceTargetContainer $priceTarget, RatingContainer $rating): static
    {
        return static::from(compact('priceTarget', 'rating'));
    }

    public static function from(array $data): static
    {
        $container = new static();
        $container->priceTarget = PriceTargetContainer::from(Arr::get($data, 'priceTarget', []));
        $container->rating = RatingContainer::from(Arr::get($data, 'rating', []));

        return $container;
    }

    public function getBuy(): ?int
    {
        return $this->rating->getBuy();
    }

    public function getHold(): ?int
    {
        return $this->rating->getHold();
    }

    public function getSell(): ?int
    {
        return $this->rating->getSell();
    }

    public function getRatingDate(): ?string
    {
        return $this->rating->getDate();
    }

    public function getPriceTargetLow(): ?float
    {
        return $this->priceTarget->getLow();
    }

    public function getPriceTargetAverage(): ?float
    {
        return $this->priceTarget->getAverage();
    }

    public function getPriceTargetHigh(): ?float
    {
        return $this->priceTarget->getHigh();
    }
}