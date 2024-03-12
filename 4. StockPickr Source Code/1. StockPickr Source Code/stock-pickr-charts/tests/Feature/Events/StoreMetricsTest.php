<?php

namespace Tests\Feature\Events;

use App\Events\MetricsUpserted;
use App\Models\Company;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use StockPickr\Common\Containers\Metrics\MetricsUpsertedContainer;

class StoreMetricsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_store_metrics_for_an_existing_company()
    {
        $company = Company::factory([
            'ticker' => 'TST',
            'financial_statements' => [
                'incomeStatements' => [
                    'totalRevenue' => [
                        'name' => 'name',
                        'formatter' => 'percent',
                        'shouldHighlightNegativeValue' => false,
                        'isInverted' => false,
                        'data' => [
                            2020 => [
                                'rawValue' => 0.33,
                                'formattedValue' => '33%'
                            ]
                        ]
                    ],
                    'netIncome' => [
                        'name' => 'name',
                        'formatter' => 'percent',
                        'shouldHighlightNegativeValue' => false,
                        'isInverted' => false,
                        'data' => [
                            2020 => [
                                'rawValue' => 0.33,
                                'formattedValue' => '33%'
                            ]
                        ]
                    ],
                    'grossProfit' => [
                        'name' => 'name',
                        'formatter' => 'percent',
                        'shouldHighlightNegativeValue' => false,
                        'isInverted' => false,
                        'data' => [
                            2020 => [
                                'rawValue' => 0.33,
                                'formattedValue' => '33%'
                            ]
                        ]
                    ],
                    'operatingIncome' => [
                        'name' => 'name',
                        'formatter' => 'percent',
                        'shouldHighlightNegativeValue' => false,
                        'isInverted' => false,
                        'data' => [
                            2020 => [
                                'rawValue' => 0.33,
                                'formattedValue' => '33%'
                            ]
                        ]
                    ],
                    'eps' => [
                        'name' => 'name',
                        'formatter' => 'percent',
                        'shouldHighlightNegativeValue' => false,
                        'isInverted' => false,
                        'data' => [
                            2020 => [
                                'rawValue' => 0.33,
                                'formattedValue' => '33%'
                            ]
                        ]
                    ]
                ],

                'balanceSheets' => [
                    'cash' => [
                        'name' => 'name',
                        'formatter' => 'percent',
                        'shouldHighlightNegativeValue' => false,
                        'isInverted' => false,
                        'data' => [
                            2020 => [
                                'rawValue' => 0.33,
                                'formattedValue' => '33%'
                            ]
                        ]
                    ],
                    'longTermDebt' => [
                        'name' => 'name',
                        'formatter' => 'percent',
                        'shouldHighlightNegativeValue' => false,
                        'isInverted' => false,
                        'data' => [
                            2020 => [
                                'rawValue' => 0.33,
                                'formattedValue' => '33%'
                            ]
                        ]
                    ],
                    'totalEquity' => [
                        'name' => 'name',
                        'formatter' => 'percent',
                        'shouldHighlightNegativeValue' => false,
                        'isInverted' => false,
                        'data' => [
                            2020 => [
                                'rawValue' => 0.33,
                                'formattedValue' => '33%'
                            ]
                        ]
                    ],
                    'totalCurrentAssets' => [
                        'name' => 'name',
                        'formatter' => 'percent',
                        'shouldHighlightNegativeValue' => false,
                        'isInverted' => false,
                        'data' => [
                            2020 => [
                                'rawValue' => 0.33,
                                'formattedValue' => '33%'
                            ]
                        ]
                    ],
                    'totalCurrentLiabilities' => [
                        'name' => 'name',
                        'formatter' => 'percent',
                        'shouldHighlightNegativeValue' => false,
                        'isInverted' => false,
                        'data' => [
                            2020 => [
                                'rawValue' => 0.33,
                                'formattedValue' => '33%'
                            ]
                        ]
                    ],
                ],

                'cashFlows' => [
                    'operatingCashFlow' => [
                        'name' => 'name',
                        'formatter' => 'percent',
                        'shouldHighlightNegativeValue' => false,
                        'isInverted' => false,
                        'data' => [
                            2020 => [
                                'rawValue' => 0.33,
                                'formattedValue' => '33%'
                            ]
                        ]
                    ],
                    'freeCashFlow' => [
                        'name' => 'name',
                        'formatter' => 'percent',
                        'shouldHighlightNegativeValue' => false,
                        'isInverted' => false,
                        'data' => [
                            2020 => [
                                'rawValue' => 0.33,
                                'formattedValue' => '33%'
                            ]
                        ]
                    ],
                    'capex' => [
                        'name' => 'name',
                        'formatter' => 'percent',
                        'shouldHighlightNegativeValue' => false,
                        'isInverted' => false,
                        'data' => [
                            2020 => [
                                'rawValue' => 0.33,
                                'formattedValue' => '33%'
                            ]
                        ]
                    ],
                ]
            ]
        ])->create();

        event(new MetricsUpserted($this->createMetricsUpsertedContainer(
            $company,
            [
                'debtToCapital' => [
                    'name' => 'name',
                    'formatter' => 'percent',
                    'shouldHighlightNegativeValue' => false,
                    'isInverted' => false,
                    'data' => [
                        2020 => [
                            'rawValue' => 0.50,
                            'formattedValue' => '50%'
                        ]
                    ]
                ],
                'currentRatio' => [
                    'name' => 'name',
                    'formatter' => 'percent',
                    'shouldHighlightNegativeValue' => false,
                    'isInverted' => false,
                    'data' => [
                        2020 => [
                            'rawValue' => 2.5,
                            'formattedValue' => '2.5'
                        ]
                    ]
                ]
            ]
        )));

        $company = Company::first();

        $this->assertDatabaseCount('companies', 1);
        $this->assertEquals(
            0.50,
            $company->metrics['debtToCapital']['data']['2020']['rawValue']
        );
        $this->assertEquals(
            2.5,
            $company->metrics['currentRatio']['data']['2020']['rawValue']
        );
    }

    private function createMetricsUpsertedContainer(
        Company $company,
        array $metricsOverride
    ): MetricsUpsertedContainer {
        return MetricsUpsertedContainer::from([
            'ticker' => $company->ticker,
            'metrics' => array_merge([
                'debtToCapital' => [
                    'name' => 'name',
                    'formatter' => 'percent',
                    'shouldHighlightNegativeValue' => false,
                    'isInverted' => false,
                    'data' => [
                        2020 => [
                            'rawValue' => 0.5,
                            'formattedValue' => '50%'
                        ]
                    ]
                ],
                'currentRatio' => [
                    'name' => 'name',
                    'formatter' => 'percent',
                    'shouldHighlightNegativeValue' => false,
                    'isInverted' => false,
                    'data' => [
                        2020 => [
                            'rawValue' => 0.5,
                            'formattedValue' => '50%'
                        ]
                    ]
                ],
                'quickRatio' => [
                    'name' => 'name',
                    'formatter' => 'percent',
                    'shouldHighlightNegativeValue' => false,
                    'isInverted' => false,
                    'data' => [
                        2020 => [
                            'rawValue' => 0.5,
                            'formattedValue' => '50%'
                        ]
                    ]
                ],
                'cashToDebt' => [
                    'name' => 'name',
                    'formatter' => 'percent',
                    'shouldHighlightNegativeValue' => false,
                    'isInverted' => false,
                    'data' => [
                        2020 => [
                            'rawValue' => 0.5,
                            'formattedValue' => '50%'
                        ]
                    ]
                ],
                'interestToOperatingProfit' => [
                    'name' => 'name',
                    'formatter' => 'percent',
                    'shouldHighlightNegativeValue' => false,
                    'isInverted' => false,
                    'data' => [
                        2020 => [
                            'rawValue' => 0.5,
                            'formattedValue' => '50%'
                        ]
                    ]
                ],
                'longTermDebtToEbitda' => [
                    'name' => 'name',
                    'formatter' => 'percent',
                    'shouldHighlightNegativeValue' => false,
                    'isInverted' => false,
                    'data' => [
                        2020 => [
                            'rawValue' => 0.5,
                            'formattedValue' => '50%'
                        ]
                    ]
                ],
                'interestCoverageRatio' => [
                    'name' => 'name',
                    'formatter' => 'percent',
                    'shouldHighlightNegativeValue' => false,
                    'isInverted' => false,
                    'data' => [
                        2020 => [
                            'rawValue' => 0.5,
                            'formattedValue' => '50%'
                        ]
                    ]
                ],
                'debtToAssets' => [
                    'name' => 'name',
                    'formatter' => 'percent',
                    'shouldHighlightNegativeValue' => false,
                    'isInverted' => false,
                    'data' => [
                        2020 => [
                            'rawValue' => 0.5,
                            'formattedValue' => '50%'
                        ]
                    ]
                ],
                'operatingCashFlowToCurrentLiabilities' => [
                    'name' => 'name',
                    'formatter' => 'percent',
                    'shouldHighlightNegativeValue' => false,
                    'isInverted' => false,
                    'data' => [
                        2020 => [
                            'rawValue' => 0.5,
                            'formattedValue' => '50%'
                        ]
                    ]
                ],
                'capexAsPercentOfRevenue' => [
                    'name' => 'name',
                    'formatter' => 'percent',
                    'shouldHighlightNegativeValue' => false,
                    'isInverted' => false,
                    'data' => [
                        2020 => [
                            'rawValue' => 0.5,
                            'formattedValue' => '50%'
                        ]
                    ]
                ],
                'capexAsPercentOfOperatingCashFlow' => [
                    'name' => 'name',
                    'formatter' => 'percent',
                    'shouldHighlightNegativeValue' => false,
                    'isInverted' => false,
                    'data' => [
                        2020 => [
                            'rawValue' => 0.5,
                            'formattedValue' => '50%'
                        ]
                    ]
                ],
                'payoutRatio' => [
                    'name' => 'name',
                    'formatter' => 'percent',
                    'shouldHighlightNegativeValue' => false,
                    'isInverted' => false,
                    'data' => [
                        2020 => [
                            'rawValue' => 0.5,
                            'formattedValue' => '50%'
                        ]
                    ]
                ],
                'roic' => [
                    'name' => 'name',
                    'formatter' => 'percent',
                    'shouldHighlightNegativeValue' => false,
                    'isInverted' => false,
                    'data' => [
                        2020 => [
                            'rawValue' => 0.5,
                            'formattedValue' => '50%'
                        ]
                    ]
                ],
                'croic' => [
                    'name' => 'name',
                    'formatter' => 'percent',
                    'shouldHighlightNegativeValue' => false,
                    'isInverted' => false,
                    'data' => [
                        2020 => [
                            'rawValue' => 0.5,
                            'formattedValue' => '50%'
                        ]
                    ]
                ],
                'rota' => [
                    'name' => 'name',
                    'formatter' => 'percent',
                    'shouldHighlightNegativeValue' => false,
                    'isInverted' => false,
                    'data' => [
                        2020 => [
                            'rawValue' => 0.5,
                            'formattedValue' => '50%'
                        ]
                    ]
                ],
                'roa' => [
                    'name' => 'name',
                    'formatter' => 'percent',
                    'shouldHighlightNegativeValue' => false,
                    'isInverted' => false,
                    'data' => [
                        2020 => [
                            'rawValue' => 0.5,
                            'formattedValue' => '50%'
                        ]
                    ]
                ],
                'roe' => [
                    'name' => 'name',
                    'formatter' => 'percent',
                    'shouldHighlightNegativeValue' => false,
                    'isInverted' => false,
                    'data' => [
                        2020 => [
                            'rawValue' => 0.5,
                            'formattedValue' => '50%'
                        ]
                    ]
                ],
                'freeCashFlowToRevenue' => [
                    'name' => 'name',
                    'formatter' => 'percent',
                    'shouldHighlightNegativeValue' => false,
                    'isInverted' => false,
                    'data' => [
                        2020 => [
                            'rawValue' => 0.5,
                            'formattedValue' => '50%'
                        ]
                    ]
                ],
                'netMargin' => [
                    'name' => 'name',
                    'formatter' => 'percent',
                    'shouldHighlightNegativeValue' => false,
                    'isInverted' => false,
                    'data' => [
                        2020 => [
                            'rawValue' => 0.5,
                            'formattedValue' => '50%'
                        ]
                    ]
                ],
                'operatingMargin' => [
                    'name' => 'name',
                    'formatter' => 'percent',
                    'shouldHighlightNegativeValue' => false,
                    'isInverted' => false,
                    'data' => [
                        2020 => [
                            'rawValue' => 0.5,
                            'formattedValue' => '50%'
                        ]
                    ]
                ],
                'grossMargin' => [
                    'name' => 'name',
                    'formatter' => 'percent',
                    'shouldHighlightNegativeValue' => false,
                    'isInverted' => false,
                    'data' => [
                        2020 => [
                            'rawValue' => 0.5,
                            'formattedValue' => '50%'
                        ]
                    ]
                ],
                'operatingCashFlowMargin' => [
                    'name' => 'name',
                    'formatter' => 'percent',
                    'shouldHighlightNegativeValue' => false,
                    'isInverted' => false,
                    'data' => [
                        2020 => [
                            'rawValue' => 0.5,
                            'formattedValue' => '50%'
                        ]
                    ]
                ],
                'sgaToGrossProfit' => [
                    'name' => 'name',
                    'formatter' => 'percent',
                    'shouldHighlightNegativeValue' => false,
                    'isInverted' => false,
                    'data' => [
                        2020 => [
                            'rawValue' => 0.5,
                            'formattedValue' => '50%'
                        ]
                    ]
                ],
                'epsGrowth' => [
                    'name' => 'name',
                    'formatter' => 'percent',
                    'shouldHighlightNegativeValue' => false,
                    'isInverted' => false,
                    'data' => [
                        2020 => [
                            'rawValue' => 0.5,
                            'formattedValue' => '50%'
                        ]
                    ]
                ],
                'netIncomeGrowth' => [
                    'name' => 'name',
                    'formatter' => 'percent',
                    'shouldHighlightNegativeValue' => false,
                    'isInverted' => false,
                    'data' => [
                        2020 => [
                            'rawValue' => 0.5,
                            'formattedValue' => '50%'
                        ]
                    ]
                ],
                'totalRevenueGrowth' => [
                    'name' => 'name',
                    'formatter' => 'percent',
                    'shouldHighlightNegativeValue' => false,
                    'isInverted' => false,
                    'data' => [
                        2020 => [
                            'rawValue' => 0.5,
                            'formattedValue' => '50%'
                        ]
                    ]
                ]
            ], $metricsOverride)
        ]);
    }
}
