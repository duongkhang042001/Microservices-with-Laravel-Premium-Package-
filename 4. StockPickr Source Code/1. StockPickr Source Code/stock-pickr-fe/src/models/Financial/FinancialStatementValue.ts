import { Nullable } from "@/global";

export interface FinancialStatementValue {
    rawValue: Nullable<number>;
    formattedValue: Nullable<string>;
    classes: string[];
}
