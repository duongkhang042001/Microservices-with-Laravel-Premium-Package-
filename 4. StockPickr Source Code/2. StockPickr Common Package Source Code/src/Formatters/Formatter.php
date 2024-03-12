<?php

declare(strict_types=1);

namespace StockPickr\Common\Formatters;

use StockPickr\Common\Formatters\Options\Options;

abstract class Formatter
{
    public const MONEY = 'money';
    public const PERCENT = 'percent';
    public const FINANCIAL_NUMBER = 'financialNumber';
    public const NUMBER = 'number';

    final private function __construct(protected Options $options)
    {
    }

    abstract public function format(?float $value): ?string;

    /**
     * Miért static?
     * Mivel nincs generics PHP -ban, ezért a format függvényt nem lehet
     * típusjelöléssel megírni, csak úgy, hogy mixed használok. Ezt
     * szeretném elkerülni, de így nem tudom ebben az osztályban
     * abstract függvényként leíni a format() -ot. Emiatt nem tudok
     * Formatter típust visszaadni, hiszen ilyenkor egy
     * Money::create()->format() warning -ot eredményezne mert nincs
     * format a Formatter osztályon. Ezért van static. Így használható
     * normális típusjelöléssel a format(), viszont nem szerepel az
     * ősosztályon, és nincs absztrakció, mindenhol a konkrét osztályokra
     * fog a cliens hivatkozni.
     *
     * @param array{decimals: ?int} $options
     */
    public static function create(array $options = ['decimals' => 2]): static
    {
        return new static(new Options($options));
    }

    protected function numberFormat(
        float $number
    ): string {
        return number_format($number, $this->options->getDecimals(), '.', ',');
    }
}
