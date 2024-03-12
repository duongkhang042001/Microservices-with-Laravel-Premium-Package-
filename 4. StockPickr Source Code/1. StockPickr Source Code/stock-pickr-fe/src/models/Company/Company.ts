import { Peer } from "./Peer";
import { Sector } from "./Sector";

export interface Company {
    ticker: string;
    name: string;
    fullName: string;
    description: string;
    industry: string;
    sector: Sector;
    employees: number;
    peers: Peer[];
}
