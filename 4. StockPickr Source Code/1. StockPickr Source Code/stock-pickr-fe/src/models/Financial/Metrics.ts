import { FinancialStatementItem } from "./FinancialStatementItem";

export interface Metrics {
    debtToCapital: FinancialStatementItem;
    currentRatio: FinancialStatementItem;
    quickRatio: FinancialStatementItem;
    cashToDebt: FinancialStatementItem;
    interestToOperatingProfit: FinancialStatementItem;
    longTermDebtToEbitda: FinancialStatementItem;
    interestCoverageRatio: FinancialStatementItem;
    debtToAssets: FinancialStatementItem;
    operatingCashFlowToCurrentLiabilities: FinancialStatementItem;
    capexAsPercentOfRevenue: FinancialStatementItem;
    capexAsPercentOfOperatingCashFlow: FinancialStatementItem;
    payoutRatio: FinancialStatementItem;
    roic: FinancialStatementItem;
    croic: FinancialStatementItem;
    rota: FinancialStatementItem;
    roa: FinancialStatementItem;
    roe: FinancialStatementItem;
    freeCashFlowToRevenue: FinancialStatementItem;
    netMargin: FinancialStatementItem;
    operatingMargin: FinancialStatementItem;
    grossMargin: FinancialStatementItem;
    operatingCashFlowMargin: FinancialStatementItem;
    sgaToGrossProfit: FinancialStatementItem;
    epsGrowth: FinancialStatementItem;
    netIncomeGrowth: FinancialStatementItem;
    totalRevenueGrowth: FinancialStatementItem;
}
