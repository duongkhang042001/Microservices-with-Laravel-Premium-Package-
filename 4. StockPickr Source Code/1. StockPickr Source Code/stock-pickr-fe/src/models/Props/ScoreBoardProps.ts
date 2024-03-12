import { BenchmarkType } from "@/models/Score";
import { Company } from "../Company/Company";

export default interface ScoreBoardProps {
    company: Company;
    benchmarkType: BenchmarkType;
}
