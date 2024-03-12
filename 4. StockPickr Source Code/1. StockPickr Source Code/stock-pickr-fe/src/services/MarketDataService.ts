import { Company } from "@/models/Company/Company";
import { Analyst } from "@/models/MarketData/Analyst";
import MarketData from "@/models/MarketData/MarketData";
import ShareData from "@/models/MarketData/ShareData";
import HttpService from "./HttpService";

export default class MarketDataService {
    async getMarketData(company: Company): Promise<MarketData> {
        const values = await Promise.all([
            this.getAnalyst(company),
            this.getShareData(company)
        ]);

        return {
            ticker: company.ticker,
            analyst: values[0],
            shareData: values[1]
        }
    }

    private async getAnalyst(company: Company): Promise<Analyst> {
        return await HttpService.get(`analyst/${company.ticker}`);
    }

    private async getShareData(company: Company): Promise<ShareData> {
        return await HttpService.get(`share-data/${company.ticker}`);
    }
}
