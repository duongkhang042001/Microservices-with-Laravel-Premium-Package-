<?php

namespace Tests\Feature\Events\CreateCharts;

use App\Events\CreateCharts;
use App\Models\Chart;
use App\Models\Company;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateGrowthChartTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_not_store_first_year_with_null_value_for_growth_chart()
    {
        $company = Company::factory([
            'ticker' => 'TST',
            'financial_statements' => [
                'incomeStatements' => [
                    'totalRevenue' => [
                        'name' => 'Total Revenue',
                        'formatter' => 'percent',
                        'shouldHighlightNegativeValue' => false,
                        'isInverted' => false,
                        'data' => [
                            2020 => [
                                'rawValue' => 121,
                                'formattedValue' => '121'
                            ],
                            2019 => [
                                'rawValue' => 110,
                                'formattedValue' => '110'
                            ],
                            2018 => [
                                'rawValue' => 100,
                                'formattedValue' => '100'
                            ],
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
                        'name' => 'Gross Profit',
                        'formatter' => 'percent',
                        'shouldHighlightNegativeValue' => false,
                        'isInverted' => false,
                        'data' => [
                            2020 => [
                                'rawValue' => 60,
                                'formattedValue' => '60'
                            ],
                            2019 => [
                                'rawValue' => 55,
                                'formattedValue' => '55'
                            ],
                            2018 => [
                                'rawValue' => 50,
                                'formattedValue' => '50'
                            ],
                        ]
                    ],
                    'operatingIncome' => [
                        'name' => 'Operating Income',
                        'formatter' => 'percent',
                        'shouldHighlightNegativeValue' => false,
                        'isInverted' => false,
                        'data' => [
                            2020 => [
                                'rawValue' => 30,
                                'formattedValue' => '30'
                            ],
                            2019 => [
                                'rawValue' => 27,
                                'formattedValue' => '27'
                            ],
                            2018 => [
                                'rawValue' => 25,
                                'formattedValue' => '25'
                            ],
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
                ],
            ],
            'metrics' => [
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
                    'name' => 'EPS Growth',
                    'formatter' => 'percent',
                    'shouldHighlightNegativeValue' => false,
                    'isInverted' => false,
                    'data' => [
                        2020 => [
                            'rawValue' => 0.10,
                            'formattedValue' => '50%'
                        ],
                        2019 => [
                            'rawValue' => null,
                            'formattedValue' => '50%'
                        ],
                    ]
                ],
                'netIncomeGrowth' => [
                    'name' => 'Net Income Growth',
                    'formatter' => 'percent',
                    'shouldHighlightNegativeValue' => false,
                    'isInverted' => false,
                    'data' => [
                        2020 => [
                            'rawValue' => 0.1,
                            'formattedValue' => '50%'
                        ],
                        2019 => [
                            'rawValue' => null,
                            'formattedValue' => '50%'
                        ],
                    ]
                ],
                'totalRevenueGrowth' => [
                    'name' => 'Total Revenue Growth',
                    'formatter' => 'percent',
                    'shouldHighlightNegativeValue' => false,
                    'isInverted' => false,
                    'data' => [
                        2020 => [
                            'rawValue' => 0.1,
                            'formattedValue' => '50%'
                        ],
                        2019 => [
                            'rawValue' => null,
                            'formattedValue' => '50%'
                        ],
                    ]
                ]
            ]
        ])->create();

        event(new CreateCharts($company));

        $chart = Chart::where('ticker', 'TST')->where('chart', 'growth')->first();

        $this->assertEquals([
            2020
        ], $chart->years);
        $this->assertEquals([
            [
                'label' => 'Total Revenue Growth',
                'data'  => [
                    0.1
                ],
                'fill'  => false
            ],
            [
                'label' => 'Net Income Growth',
                'data'  => [
                    0.1
                ],
                'fill'  => false
            ],
            [
                'label' => 'EPS Growth',
                'data'  => [
                    0.1
                ],
                'fill'  => false
            ]
        ], $chart->data);
    }
}
