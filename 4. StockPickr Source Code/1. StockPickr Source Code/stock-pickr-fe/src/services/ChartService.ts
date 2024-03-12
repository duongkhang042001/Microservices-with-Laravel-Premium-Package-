import { Chart, ChartGroup } from "@/models/Chart";
import { Company } from "@/models/Company/Company";
import HttpService from "./HttpService";

export default class ChartService {
    static get financialNumber() {
        return 'financial-number';
    }

    static get percent() {
        return 'percent';
    }

    static get money() {
        return 'money';
    }

    async getChartGroup(company: Company, group: string): Promise<ChartGroup> {
        const data: { [key: string]: Chart } = await HttpService.get(`charts/${company.ticker}/${group}`);
        const chartGroup = new Map;

        Object.keys(data)
            .forEach((key: string) => chartGroup.set(key, data[key]));

        return chartGroup;
    }

    static format(number: number, decimals: number, normalizer: string) {
        switch (normalizer) {
            case ChartService.percent: return ChartService.formatPercent(number, decimals);
            case ChartService.financialNumber: return ChartService.formatFinancialNumber(number, decimals);
            case ChartService.money: return ChartService.formatMoney(number, decimals);
            default: throw Error('Invalid data format: ' + normalizer);
        }
    }

    static formatPercent(number: number, decimals: number) {
        return (number * 100).toFixed(decimals) + '%';
    }

    static formatFinancialNumber(number: number, decimals: number) {
        const suffix = '$ ';

        if (number == 0) {
            return suffix + 0;
        }

        if (Math.abs(number) >= 1000) {
            return suffix + (number / 1000).toFixed(decimals) + 'B';
        }

        return suffix + number.toFixed(decimals) + 'M';
    }

    static formatMoney(number: number, decimals: number) {
        return '$ ' + number.toFixed(decimals);
    }

    static getDimensions() {
        return {
            height: '500px',
            width: '100%'
        }
    }

    static setCanvasDimensions() {
        const canvases = document.getElementsByTagName('canvas');
        for (const canvas of canvases) {
            if (canvas.parentNode) {
                (canvas.parentNode as any).style.height = ChartService.getDimensions().height;
                (canvas.parentNode as any).style.width = ChartService.getDimensions().width;
                (canvas.parentNode as any).style.position = 'relative';
            }
        }
    }
}
