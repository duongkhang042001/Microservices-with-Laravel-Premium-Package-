import { Company } from "@/models/Company/Company";
import { MetricsMedian } from "@/models/Metric";
import HttpService from "./HttpService";

export default class MetricsService {
    async getMediansForCompany(company: Company): Promise<MetricsMedian> {
        const data: MetricsMedian = await HttpService.get(`metrics/medians/${company.ticker}`);
        data.ticker = company.ticker;
        return data;
    }

    async getMediansForAllCompany(): Promise<MetricsMedian> {
        return await HttpService.get(`metrics/medians`);
    }
    async getMediansForSector(sector: string): Promise<MetricsMedian> {
        return await HttpService.get(`metrics/medians/sector/${sector}`);
    }
}
