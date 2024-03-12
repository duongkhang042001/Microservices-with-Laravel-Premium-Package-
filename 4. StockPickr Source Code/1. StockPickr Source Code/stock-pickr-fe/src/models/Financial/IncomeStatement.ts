import { FinancialStatementItem } from "./FinancialStatementItem";

export interface IncomeStatement {
    totalRevenue: FinancialStatementItem;
    costOfRevenue: FinancialStatementItem;
    grossProfit: FinancialStatementItem;
    operatingIncome: FinancialStatementItem;
    pretaxIncome: FinancialStatementItem;
    incomeTax: FinancialStatementItem;
    interestExpense: FinancialStatementItem;
    researchAndDevelopment: FinancialStatementItem;
    sellingGeneralAdministrative: FinancialStatementItem;
    netIncome: FinancialStatementItem;
    ebit: FinancialStatementItem;
    eps: FinancialStatementItem;
}
