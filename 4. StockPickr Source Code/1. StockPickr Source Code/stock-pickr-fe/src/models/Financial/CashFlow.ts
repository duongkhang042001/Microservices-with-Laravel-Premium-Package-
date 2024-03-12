import { FinancialStatementItem } from "./FinancialStatementItem";

export interface CashFlow {
    netIncome: FinancialStatementItem;
    operatingCashFlow: FinancialStatementItem;
    capex: FinancialStatementItem;
    cashDividendPaid: FinancialStatementItem;
    deprecationAmortization: FinancialStatementItem;
    freeCashFlow: FinancialStatementItem;
    cashFromFinancing: FinancialStatementItem;
    cashFromInvesting: FinancialStatementItem;
}
