import { FinancialStatementItem } from "./FinancialStatementItem";

export interface BalanceSheet {
    cash: FinancialStatementItem;
    currentCash: FinancialStatementItem;
    totalCurrentAssets: FinancialStatementItem;
    netIntangibleAssets: FinancialStatementItem;
    tangibleAssets: FinancialStatementItem;
    shortTermInvestments: FinancialStatementItem;
    currentAccountAndReceivables: FinancialStatementItem;
    totalAssets: FinancialStatementItem;
    totalEquity: FinancialStatementItem;
    currentPortionOfLongTermDebt: FinancialStatementItem;
    totalCurrentLiabilities: FinancialStatementItem;
    longTermDebt: FinancialStatementItem;
    totalLiabilities: FinancialStatementItem;
}
