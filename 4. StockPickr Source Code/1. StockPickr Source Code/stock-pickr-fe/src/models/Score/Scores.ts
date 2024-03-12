export interface Scores {
    [propName: string]: any;
    ticker: string;
    sumamry: {
        totalScores: number;
        totalScorePercent: string;
        maxPossibleScores: number;
        position: number;
        positionPercentile: number;
    };
    debtToCapital: number;
    currentRatio: number;
    quickRatio: number;
    cashToDebt: number;
    interestToOperatingProfit: number;
    longTermDebtToEbitda: number;
    interestCoverageRatio: number;
    debtToAssets: number;
    operatingCashFlowToCurrentLiabilities: number;
    capexAsPercentOfRevenue: number;
    capexAsPercentOfOperatingCashFlow: number;
    payoutRatio: number;
    roic: number;
    croic: number;
    rota: number;
    roa: number;
    roe: number;
    freeCashFlowToRevenue: number;
    netMargin: number;
    operatingMargin: number;
    grossMargin: number;
    operatingCashFlowMargin: number;
    sgaToGrossProfit: number;
    epsGrowth: number;
    netIncomeGrowth: number;
    totalRevenueGrowth: number;
}
