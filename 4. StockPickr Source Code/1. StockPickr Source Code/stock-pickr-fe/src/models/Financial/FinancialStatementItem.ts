import { FinancialStatementValue } from "./FinancialStatementValue";

export interface FinancialStatementItem {
    name: string;
    formatter: string;
    shouldHighlightNegativeValue: boolean;
    isInverted: boolean;
    data: { [key: string]: FinancialStatementValue };
}
