import { Analyst } from "./Analyst";
import ShareData from "./ShareData";

export default interface MarketData {
    ticker: string;
    shareData: ShareData;
    analyst: Analyst;
}
