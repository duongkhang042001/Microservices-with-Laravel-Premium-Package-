<?php

namespace App\ChartConfigs;

abstract class ChartConfig
{
    /**
     * @return string[]
     */
    abstract public function financials(): array;
    abstract public function type(): string;
    abstract public function normalizer(): string;
    abstract public function title(): string;
    abstract public function slug(): string;

    /**
     * @return array{responsive: bool, maintainAspectRatio: bool, title: array, plugins: array}
     */
    public function config(): array
    {
        return [
            'responsive'            => true,
            'maintainAspectRatio'   => false,
            'title' => [
                'display'   => true,
                'text'      => $this->title(),
                'fontSize'  => 18
            ],
            'plugins' => [
                'colorschemes' => [
                    'scheme' => 'tableau.Tableau10'
                ]
            ]
        ];

    }
}
