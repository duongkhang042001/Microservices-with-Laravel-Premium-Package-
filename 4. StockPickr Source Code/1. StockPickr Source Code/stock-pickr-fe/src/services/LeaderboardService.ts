import { LeaderboardCompany } from '@/models/Company';
import HttpService from './HttpService';

export default class LeaderboardService {
    async get(limit: number, offset: number): Promise<Array<LeaderboardCompany>> {
        return await HttpService.get(`queries/leaderboard?limit=${limit}&offset=${offset}`);
    }
}
