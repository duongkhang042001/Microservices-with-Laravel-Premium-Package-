<template>
<div v-if="scores.grossMargin && medians.own.grossMargin" class="container">
    <div class="table-responsive box">
        <table class="table light-hover scores">
            <thead>
            <tr>
                <th></th>
                <th></th>
                <th>{{ getTitle() }}</th>
                <th>{{ company.ticker }} Value</th>
                <th>{{ company.ticker }} Score</th>
            </tr>
            </thead>
            <tbody>
            <tr class="summary no-hover">
                <td class="text-left">Max possible scores:</td>
                <td class="font-weight-bold br">{{ scores.summary.maxPossibleScores }}</td>
                <td colspan="3"></td>
            </tr>
            <tr class="summary no-hover">
                <td class="text-left">{{ company.ticker }} scores:</td>
                <td class="font-weight-bold br">{{ scores.summary.totalScores }}</td>
                <td colspan="3"></td>
            </tr>
            <tr class="summary no-hover">
                <td class="text-left bb">As a percentage:</td>
                <td class="font-weight-bold br bb">{{ scores.summary.totalScorePercent }}</td>
                <td colspan="3"></td>
            </tr>

            <tr class="no-hover">
                <td colspan="5"></td>
            </tr>

            <tr class="category">
                <td class="font-weight-bold text-left">Margins</td>
                <td colspan="4"></td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ medians.own.grossMargin.name }}</td>
                <td>{{ medians.others.grossMargin.value }}</td>
                <td :class="getClass(scores.grossMargin)" class="font-weight-bolder">
                    {{ medians.own.grossMargin.value }}
                </td>
                <td :class="getClass(scores.grossMargin)" class="font-weight-bolder">{{ scores.grossMargin }}</td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ medians.own.operatingMargin.name }}</td>
                <td>{{ medians.others.operatingMargin.value }}</td>
                <td :class="getClass(scores.operatingMargin)" class="font-weight-bolder">
                    {{ medians.own.operatingMargin.value }}
                </td>
                <td :class="getClass(scores.operatingMargin)" class="font-weight-bolder">{{ scores.operatingMargin }}</td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ medians.own.netMargin.name }}</td>
                <td>{{ medians.others.netMargin.value }}</td>
                <td :class="getClass(scores.netMargin)" class="font-weight-bolder">
                    {{ medians.own.netMargin.value }}
                </td>
                <td :class="getClass(scores.netMargin)" class="font-weight-bolder">{{ scores.netMargin }}</td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ medians.own.sgaToGrossProfit.name }}</td>
                <td>{{ medians.others.sgaToGrossProfit.value }}</td>
                <td :class="getClass(scores.sgaToGrossProfit)" class="font-weight-bolder">
                    {{ medians.own.sgaToGrossProfit.value }}
                </td>
                <td :class="getClass(scores.sgaToGrossProfit)" class="font-weight-bolder">{{ scores.sgaToGrossProfit }}</td>
            </tr>

            <tr class="category">
                <td class="font-weight-bold text-left">Growth</td>
                <td colspan="4"></td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ medians.own.totalRevenueGrowth.name }}</td>
                <td>{{ medians.others.totalRevenueGrowth.value }}</td>
                <td :class="getClass(scores.totalRevenueGrowth)" class="font-weight-bolder">
                    {{ medians.own.totalRevenueGrowth.value }}
                </td>
                <td :class="getClass(scores.totalRevenueGrowth)" class="font-weight-bolder">{{ scores.totalRevenueGrowth }}</td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ medians.own.netIncomeGrowth.name }}</td>
                <td>{{ medians.others.netIncomeGrowth.value }}</td>
                <td :class="getClass(scores.netIncomeGrowth)" class="font-weight-bolder">
                    {{ medians.own.netIncomeGrowth.value }}
                </td>
                <td :class="getClass(scores.netIncomeGrowth)" class="font-weight-bolder">{{ scores.netIncomeGrowth }}</td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ medians.own.epsGrowth.name }}</td>
                <td>{{ medians.others.epsGrowth.value }}</td>
                <td :class="getClass(scores.epsGrowth)" class="font-weight-bolder">
                    {{ medians.own.epsGrowth.value }}
                </td>
                <td :class="getClass(scores.epsGrowth)" class="font-weight-bolder">{{ scores.epsGrowth }}</td>
            </tr>

            <tr class="category">
                <td class="font-weight-bold text-left">Liquidity</td>
                <td colspan="4"></td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ medians.own.currentRatio.name }}</td>
                <td>{{ medians.others.currentRatio.value }}</td>
                <td :class="getClass(scores.currentRatio)" class="font-weight-bolder">
                    {{ medians.own.currentRatio.value }}
                </td>
                <td :class="getClass(scores.currentRatio)" class="font-weight-bolder">{{ scores.currentRatio }}</td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ medians.own.quickRatio.name }}</td>
                <td>{{ medians.others.quickRatio.value }}</td>
                <td :class="getClass(scores.quickRatio)" class="font-weight-bolder">
                    {{ medians.own.quickRatio.value }}
                </td>
                <td :class="getClass(scores.quickRatio)" class="font-weight-bolder">{{ scores.quickRatio }}</td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ medians.own.operatingCashFlowToCurrentLiabilities.name }}</td>
                <td>{{ medians.others.operatingCashFlowToCurrentLiabilities.value }}</td>
                <td :class="getClass(scores.operatingCashFlowToCurrentLiabilities)" class="font-weight-bolder">
                    {{ medians.own.operatingCashFlowToCurrentLiabilities.value }}
                </td>
                <td :class="getClass(scores.operatingCashFlowToCurrentLiabilities)" class="font-weight-bolder">{{ scores.operatingCashFlowToCurrentLiabilities }}</td>
            </tr>

            <tr class="category">
                <td class="font-weight-bold text-left">Solvency</td>
                <td colspan="4"></td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ medians.own.debtToCapital.name }}</td>
                <td>{{ medians.others.debtToCapital.value }}</td>
                <td :class="getClass(scores.debtToCapital)" class="font-weight-bolder">
                    {{ medians.own.debtToCapital.value }}
                </td>
                <td :class="getClass(scores.debtToCapital)" class="font-weight-bolder">{{ scores.debtToCapital }}</td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ medians.own.debtToAssets.name }}</td>
                <td>{{ medians.others.debtToAssets.value }}</td>
                <td :class="getClass(scores.debtToAssets)" class="font-weight-bolder">
                    {{ medians.own.debtToAssets.value }}
                </td>
                <td :class="getClass(scores.debtToAssets)" class="font-weight-bolder">{{ scores.debtToAssets }}</td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ medians.own.cashToDebt.name }}</td>
                <td>{{ medians.others.cashToDebt.value }}</td>
                <td :class="getClass(scores.cashToDebt)" class="font-weight-bolder">
                    {{ medians.own.cashToDebt.value }}
                </td>
                <td :class="getClass(scores.cashToDebt)" class="font-weight-bolder">{{ scores.cashToDebt }}</td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ medians.own.interestToOperatingProfit.name }}</td>
                <td>{{ medians.others.interestToOperatingProfit.value }}</td>
                <td :class="getClass(scores.interestToOperatingProfit)" class="font-weight-bolder">
                    {{ medians.own.interestToOperatingProfit.value }}
                </td>
                <td :class="getClass(scores.interestToOperatingProfit)" class="font-weight-bolder">{{ scores.interestToOperatingProfit }}</td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ medians.own.interestCoverageRatio.name }}</td>
                <td>{{ medians.others.interestCoverageRatio.value }}</td>
                <td :class="getClass(scores.interestCoverageRatio)" class="font-weight-bolder">
                    {{ medians.own.interestCoverageRatio.value }}
                </td>
                <td :class="getClass(scores.interestCoverageRatio)" class="font-weight-bolder">{{ scores.interestCoverageRatio }}</td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ medians.own.longTermDebtToEbitda.name }}</td>
                <td>{{ medians.others.longTermDebtToEbitda.value }}</td>
                <td :class="getClass(scores.longTermDebtToEbitda)" class="font-weight-bolder">
                    {{ medians.own.longTermDebtToEbitda.value }}
                </td>
                <td :class="getClass(scores.longTermDebtToEbitda)" class="font-weight-bolder">{{ scores.longTermDebtToEbitda }}</td>
            </tr>

            <tr class="category">
                <td class="font-weight-bold text-left">Performance</td>
                <td colspan="4"></td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ medians.own.roic.name }}</td>
                <td>{{ medians.others.roic.value }}</td>
                <td :class="getClass(scores.roic)" class="font-weight-bolder">
                    {{ medians.own.roic.value }}
                </td>
                <td :class="getClass(scores.roic)" class="font-weight-bolder">{{ scores.roic }}</td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ medians.own.croic.name }}</td>
                <td>{{ medians.others.croic.value }}</td>
                <td :class="getClass(scores.croic)" class="font-weight-bolder">
                    {{ medians.own.croic.value }}
                </td>
                <td :class="getClass(scores.croic)" class="font-weight-bolder">{{ scores.croic }}</td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ medians.own.roe.name }}</td>
                <td>{{ medians.others.roe.value }}</td>
                <td :class="getClass(scores.roe)" class="font-weight-bolder">
                    {{ medians.own.roe.value }}
                </td>
                <td :class="getClass(scores.roe)" class="font-weight-bolder">{{ scores.roe }}</td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ medians.own.roa.name }}</td>
                <td>{{ medians.others.roa.value }}</td>
                <td :class="getClass(scores.roa)" class="font-weight-bolder">
                    {{ medians.own.roa.value }}
                </td>
                <td :class="getClass(scores.roa)" class="font-weight-bolder">{{ scores.roa }}</td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ medians.own.rota.name }}</td>
                <td>{{ medians.others.rota.value }}</td>
                <td :class="getClass(scores.rota)" class="font-weight-bolder">
                    {{ medians.own.rota.value }}
                </td>
                <td :class="getClass(scores.rota)" class="font-weight-bolder">{{ scores.rota }}</td>
            </tr>

            <tr class="category">
                <td class="font-weight-bold text-left">Cash Flow</td>
                <td colspan="4"></td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ medians.own.payoutRatio.name }}</td>
                <td>{{ medians.others.payoutRatio.value }}</td>
                <td :class="getClass(scores.payoutRatio)" class="font-weight-bolder">
                    {{ medians.own.payoutRatio.value }}
                </td>
                <td :class="getClass(scores.payoutRatio)" class="font-weight-bolder">{{ scores.payoutRatio }}</td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ medians.own.freeCashFlowToRevenue.name }}</td>
                <td>{{ medians.others.freeCashFlowToRevenue.value }}</td>
                <td :class="getClass(scores.freeCashFlowToRevenue)" class="font-weight-bolder">
                    {{ medians.own.freeCashFlowToRevenue.value }}
                </td>
                <td :class="getClass(scores.freeCashFlowToRevenue)" class="font-weight-bolder">{{ scores.freeCashFlowToRevenue }}</td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ medians.own.operatingCashFlowMargin.name }}</td>
                <td>{{ medians.others.operatingCashFlowMargin.value }}</td>
                <td :class="getClass(scores.operatingCashFlowMargin)" class="font-weight-bolder">
                    {{ medians.own.operatingCashFlowMargin.value }}
                </td>
                <td :class="getClass(scores.operatingCashFlowMargin)" class="font-weight-bolder">{{ scores.operatingCashFlowMargin }}</td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ medians.own.capexAsPercentOfRevenue.name }}</td>
                <td>{{ medians.others.capexAsPercentOfRevenue.value }}</td>
                <td :class="getClass(scores.capexAsPercentOfRevenue)" class="font-weight-bolder">
                    {{ medians.own.capexAsPercentOfRevenue.value }}
                </td>
                <td :class="getClass(scores.capexAsPercentOfRevenue)" class="font-weight-bolder">{{ scores.capexAsPercentOfRevenue }}</td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ medians.own.capexAsPercentOfOperatingCashFlow.name }}</td>
                <td>{{ medians.others.capexAsPercentOfOperatingCashFlow.value }}</td>
                <td :class="getClass(scores.capexAsPercentOfOperatingCashFlow)" class="font-weight-bolder">
                    {{ medians.own.capexAsPercentOfOperatingCashFlow.value }}
                </td>
                <td :class="getClass(scores.capexAsPercentOfOperatingCashFlow)" class="font-weight-bolder">{{ scores.capexAsPercentOfOperatingCashFlow }}</td>
            </tr>
            <tr class="no-hover">
                <td colspan="5"></td>
            </tr>
            <tr class="no-hover">
                <td colspan="5">{{ getHelpText() }}</td>
            </tr>
            <tr class="no-hover">
                <td colspan="5">Scores are given from 1 through 4, where 4 is above average and 1 is the worst</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
</template>

<script lang="ts">
import ScoreService from '@/services/ScoreService';
import MetricsService from '@/services/MetricsService';
import { onMounted, PropType, Ref, ref } from 'vue';
import * as Score from '@/models/Score';
import { ScoreBoardProps } from '@/models/Props';
import { Company } from '@/models/Company/Company';
import { MetricsMedian } from '@/models/Metric';

export default {
    name: 'CompanyScoreBoard',
    props: {
        company: {
            type: Object as PropType<Company>,
            required: true,
            validator: (company: Company) => !!company.ticker
        },
        benchmarkType: {
            type: String as PropType<Score.BenchmarkType>,
            required: true,
            validator: (type: string) => type === Score.BenchmarkType.ALL || type === Score.BenchmarkType.SECTOR
        }
    },

    setup(props: ScoreBoardProps) {
        const metricsService = new MetricsService();
        const scoreService = new ScoreService();
        const medians = ref({
            own: {},
            others: {}
        });
        const scores: Ref<Score.Scores> = ref({} as Score.Scores);

        const fetch = async () => {
            const promises: Array<Promise<any>>
                = [metricsService.getMediansForCompany(props.company)];

            if (props.benchmarkType === Score.BenchmarkType.ALL) {
                promises.push(metricsService.getMediansForAllCompany());
                promises.push(scoreService.getScores(props.company));
            } else {
                promises.push(metricsService.getMediansForSector(props.company.sector?.name || ''));
                promises.push(scoreService.getSectorScores(props.company));
            }

            const result = await Promise.all(promises);
            medians.value.own = result[0];
            medians.value.others = result[1];
            scores.value = result[2];
        };

        onMounted(async () => {
            await fetch();
        });

        const getClass = (score: number): string => {
            switch (score) {
                case 1: return 'text-danger';
                case 2: return 'text-warning';
                case 3: return 'text-normal';
                case 4: return 'text-success';
                default: return 'text-normal';
            }
        };

        const getTitle = () => {
            return props.benchmarkType === Score.BenchmarkType.ALL
                ? 'Median values of all companies'
                : 'Median values of ' + props.company.sector?.name;
        };

        const getHelpText = () => {
            return props.benchmarkType === Score.BenchmarkType.ALL
                ? `This table shows you how ${props.company.ticker} compares to ALL other companies`
                : `This table shows you how ${props.company.ticker} compares to ${props.company.sector.name} companies`;
        };

        return {
            getClass,
            scores,
            medians,
            getTitle,
            getHelpText
        };
    }
}
</script>
<style lang="scss" scoped>
tr.category {
    td {
       font-size: 14px;
       border-bottom: 2px solid #444 !important;
    }
}
.bb {
    border-bottom: 2px solid #444 !important;
}
.br {
    border-right: 2px solid #444 !important;
}
</style>
