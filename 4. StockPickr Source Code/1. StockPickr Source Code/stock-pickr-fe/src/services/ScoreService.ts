import { Scores } from '@/models/Score';
import { Company } from '@/models/Company/Company';
import HttpService from './HttpService';

export default class ScoreService {
    async getScores(company: Company): Promise<Scores> {
        const data: Scores = await HttpService.get(`scores/${company.ticker}`);
        data.ticker = company.ticker;
        return data;
    }

    async getSectorScores(company: Company): Promise<Scores> {
        const data: Scores = await HttpService.get(`scores/${company.ticker}/sector`);
        data.ticker = company.ticker;
        return data;
    }
}
