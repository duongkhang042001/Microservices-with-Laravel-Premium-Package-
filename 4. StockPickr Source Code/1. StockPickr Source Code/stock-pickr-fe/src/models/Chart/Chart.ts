import { ChartData } from './ChartData';
import { ChartType } from './ChartType';

export class Chart {
    public config: Map<string, any>;
    public data: ChartData;
    public type: ChartType;
    public nomalizer: string;

    constructor ({ config, data, type, normalizer }: { config: Map<string, any>; data: ChartData; type: ChartType; normalizer: string }) {
       this.config = config;
       this.data = data;
       this.type = type;
       this.nomalizer = normalizer;
    }
}
