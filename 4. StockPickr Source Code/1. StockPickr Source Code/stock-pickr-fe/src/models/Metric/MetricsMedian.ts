export interface MetricsMedian {
    ticker: string;
    debtToCapital: MetricMedianItem;
    currentRatio: MetricMedianItem;
    quickRatio: MetricMedianItem;
    cashToDebt: MetricMedianItem;
    interestToOperatingProfit: MetricMedianItem;
    longTermDebtToEbitda: MetricMedianItem;
    interestCoverageRatio: MetricMedianItem;
    debtToAssets: MetricMedianItem;
    operatingCashFlowToCurrentLiabilities: MetricMedianItem;
    capexAsPercentOfRevenue: MetricMedianItem;
    capexAsPercentOfOperatingCashFlow: MetricMedianItem;
    payoutRatio: MetricMedianItem;
    roic: MetricMedianItem;
    croic: MetricMedianItem;
    rota: MetricMedianItem;
    roa: MetricMedianItem;
    roe: MetricMedianItem;
    freeCashFlowToRevenue: MetricMedianItem;
    netMargin: MetricMedianItem;
    operatingMargin: MetricMedianItem;
    grossMargin: MetricMedianItem;
    operatingCashFlowMargin: MetricMedianItem;
    sgaToGrossProfit: MetricMedianItem;
    epsGrowth: MetricMedianItem;
    netIncomeGrowth: MetricMedianItem;
    totalRevenueGrowth: MetricMedianItem;
}

export interface MetricMedianItem {
    name: string;
    value: string;
}
