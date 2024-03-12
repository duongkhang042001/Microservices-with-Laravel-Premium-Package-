import { FinancialStatementType, IncomeStatement, BalanceSheet, CashFlow, Metrics } from '@/models/Financial';
import { Company } from '@/models/Company/Company';
import HttpService from './HttpService';

export default class CompanyService {
    static readonly logView = 'view';
    static readonly logSearch = 'search';

    async getByTicker(ticker: string): Promise<Company> {
        return await HttpService.get(`companies/${ticker}`);
    }

    async search<T>(searchTerm: string): Promise<Array<T>> {
        const companies = await HttpService.get(`queries/search?q=${searchTerm}`);
        this.log(CompanyService.logSearch, searchTerm);

        return companies;
    }

    async log(action: string, payload: string): Promise<any> {
        return await HttpService.post('companies/logs', {
            action, payload
        });
    }

    async getCount(): Promise<number> {
        let count: any = sessionStorage.getItem('count');
        if (count) {
            return parseInt(count);
        }

        count = await HttpService.get(`queries/count`);
        sessionStorage.setItem('count', count);

        return count;
    }

    async getIncomeStatement(company: Company): Promise<IncomeStatement> {
        return await HttpService.get(`companies/${company.ticker}/financial-statements/${FinancialStatementType.IncomeStatement}`);
    }

    async getBalanceSheet(company: Company): Promise<BalanceSheet> {
        return await HttpService.get(`companies/${company.ticker}/financial-statements/${FinancialStatementType.BalanceSheet}`);
    }

    async getCashFlow(company: Company): Promise<CashFlow> {
        return await HttpService.get(`companies/${company.ticker}/financial-statements/${FinancialStatementType.CashFlow}`);
    }

    async getMetrics(company: Company): Promise<Metrics> {
        return await HttpService.get(`metrics/${company.ticker}`);
    }
}
