<?php

namespace Tests\Feature\Events\CreateCharts;

use App\Events\CreateCharts;
use App\Models\Chart;
use App\Models\Company;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateIncomeChartTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_store_income_chart()
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
                        'name' => 'Net Income',
                        'formatter' => 'percent',
                        'shouldHighlightNegativeValue' => false,
                        'isInverted' => false,
                        'data' => [
                            2020 => [
                                'rawValue' => 12.1,
                                'formattedValue' => '12.1'
                            ],
                            2019 => [
                                'rawValue' => 11,
                                'formattedValue' => '11'
                            ],
                            2018 => [
                                'rawValue' => 10,
                                'formattedValue' => '10'
                            ],
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
            ]
        ])->create();

        event(new CreateCharts($company));

        $chart = Chart::where('ticker', 'TST')->where('chart', 'income')->first();

        $this->assertEquals([
            2018, 2019, 2020
        ], $chart->years);
        $this->assertEquals([
            [
                'label' => 'Net Income',
                'data'  => [
                    10, 11, 12.1
                ],
                'fill'  => false
            ],
        ], $chart->data);
    }
}
