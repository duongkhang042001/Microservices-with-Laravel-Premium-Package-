<template>
<div v-if="ownScores.grossMargin && ownMedians.grossMargin" class="container">
    <div class="table-responsive box">
        <table class="table light-hover scores">
            <thead>
            <tr>
                <th colspan="2"></th>
                <th>{{ company.ticker }}</th>
                <th v-for="rival in rivals" :key="`rival-${rival.ticker}`">
                    {{ rival.ticker }}
                    <a class="btn-link" @click="onRemoveRivalClick(rival.ticker)"> (-)</a>
                </th>
                <th>
                    <a href="#" class="add-rival" @click.prevent="onAddRivalClick()">+</a>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr v-if="visiblePeers.length" class="no-hover">
                <td :colspan="numberOfColumns" class="text-left peers">
                    Here are some peers to get started:
                    <a v-for="peer of visiblePeers" :key="peer.ticker" class="ml-1" href="#" @click.prevent="onCompanySelected(peer)">
                        {{ peer.ticker }}
                    </a>
                </td>
            </tr>
            <tr class="summary">
                <td colspan="2" class="text-left">Total score as percent</td>
                <td>{{ ownScores.summary.totalScorePercent }}</td>
                <td v-for="rivalScore in rivalsScores" :key="`rival-total-score-${rivalScore.ticker}`">
                    {{ rivalScore.summary.totalScorePercent }}
                </td>
                <td></td>
            </tr>
            <tr class="no-hover">
                <td :colspan="numberOfColumns"></td>
            </tr>

            <tr class="category">
                <td class="font-weight-bold text-left">Margins</td>
                <td :colspan="numberOfColumns - 1"></td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ ownMedians.grossMargin.name }}</td>
                <td :class="getClass(ownScores.grossMargin)" class="font-weight-bolder">
                    {{ ownMedians.grossMargin.value }}
                </td>
                <td v-for="rival in rivals" :key="`rival-value-${rival.ticker}`" :class="getRivalClass(rival.ticker, 'grossMargin')" class="font-weight-bolder">
                    {{ rival.grossMargin.value }}
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ ownMedians.operatingMargin.name }}</td>
                <td :class="getClass(ownScores.operatingMargin)" class="font-weight-bolder">
                    {{ ownMedians.operatingMargin.value }}
                </td>
                <td v-for="rival in rivals" :key="`rival-value-${rival.ticker}`" :class="getRivalClass(rival.ticker, 'operatingMargin')" class="font-weight-bolder">
                    {{ rival.operatingMargin.value }}
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ ownMedians.netMargin.name }}</td>
                <td :class="getClass(ownScores.netMargin)" class="font-weight-bolder">
                    {{ ownMedians.netMargin.value }}
                </td>
                <td v-for="rival in rivals" :key="`rival-value-${rival.ticker}`" :class="getRivalClass(rival.ticker, 'netMargin')" class="font-weight-bolder">
                    {{ rival.netMargin.value }}
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ ownMedians.sgaToGrossProfit.name }}</td>
                <td :class="getClass(ownScores.sgaToGrossProfit)" class="font-weight-bolder">
                    {{ ownMedians.sgaToGrossProfit.value }}
                </td>
                <td v-for="rival in rivals" :key="`rival-value-${rival.ticker}`" :class="getRivalClass(rival.ticker, 'sgaToGrossProfit')" class="font-weight-bolder">
                    {{ rival.sgaToGrossProfit.value }}
                </td>
                <td></td>
            </tr>

            <tr class="category">
                <td class="font-weight-bold text-left">Growth</td>
                <td :colspan="numberOfColumns - 1"></td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ ownMedians.totalRevenueGrowth.name }}</td>
                <td :class="getClass(ownScores.totalRevenueGrowth)" class="font-weight-bolder">
                    {{ ownMedians.totalRevenueGrowth.value }}
                </td>
                <td v-for="rival in rivals" :key="`rival-value-${rival.ticker}`" :class="getRivalClass(rival.ticker, 'totalRevenueGrowth')" class="font-weight-bolder">
                    {{ rival.totalRevenueGrowth.value }}
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ ownMedians.netIncomeGrowth.name }}</td>
                <td :class="getClass(ownScores.netIncomeGrowth)" class="font-weight-bolder">
                    {{ ownMedians.netIncomeGrowth.value }}
                </td>
                <td v-for="rival in rivals" :key="`rival-value-${rival.ticker}`" :class="getRivalClass(rival.ticker, 'netIncomeGrowth')" class="font-weight-bolder">
                    {{ rival.netIncomeGrowth.value }}
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ ownMedians.epsGrowth.name }}</td>
                <td :class="getClass(ownScores.epsGrowth)" class="font-weight-bolder">
                    {{ ownMedians.epsGrowth.value }}
                </td>
                <td v-for="rival in rivals" :key="`rival-value-${rival.ticker}`" :class="getRivalClass(rival.ticker, 'epsGrowth')" class="font-weight-bolder">
                    {{ rival.epsGrowth.value }}
                </td>
                <td></td>
            </tr>

            <tr class="category">
                <td class="font-weight-bold text-left">Liquidity</td>
                <td :colspan="numberOfColumns - 1"></td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ ownMedians.currentRatio.name }}</td>
                <td :class="getClass(ownScores.currentRatio)" class="font-weight-bolder">
                    {{ ownMedians.currentRatio.value }}
                </td>
                <td v-for="rival in rivals" :key="`rival-value-${rival.ticker}`" :class="getRivalClass(rival.ticker, 'currentRatio')" class="font-weight-bolder">
                    {{ rival.currentRatio.value }}
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ ownMedians.quickRatio.name }}</td>
                <td :class="getClass(ownScores.quickRatio)" class="font-weight-bolder">
                    {{ ownMedians.quickRatio.value }}
                </td>
                <td v-for="rival in rivals" :key="`rival-value-${rival.ticker}`" :class="getRivalClass(rival.ticker, 'quickRatio')" class="font-weight-bolder">
                    {{ rival.quickRatio.value }}
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ ownMedians.operatingCashFlowToCurrentLiabilities.name }}</td>
                <td :class="getClass(ownScores.operatingCashFlowToCurrentLiabilities)" class="font-weight-bolder">
                    {{ ownMedians.operatingCashFlowToCurrentLiabilities.value }}
                </td>
                <td v-for="rival in rivals" :key="`rival-value-${rival.ticker}`" :class="getRivalClass(rival.ticker, 'operatingCashFlowToCurrentLiabilities')" class="font-weight-bolder">
                    {{ rival.operatingCashFlowToCurrentLiabilities.value }}
                </td>
                <td></td>
            </tr>

            <tr class="category">
                <td class="font-weight-bold text-left">Solvency</td>
                <td :colspan="numberOfColumns - 1"></td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ ownMedians.debtToCapital.name }}</td>
                <td :class="getClass(ownScores.debtToCapital)" class="font-weight-bolder">
                    {{ ownMedians.debtToCapital.value }}
                </td>
                <td v-for="rival in rivals" :key="`rival-value-${rival.ticker}`" :class="getRivalClass(rival.ticker, 'debtToCapital')" class="font-weight-bolder">
                    {{ rival.debtToCapital.value }}
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ ownMedians.debtToAssets.name }}</td>
                <td :class="getClass(ownScores.debtToAssets)" class="font-weight-bolder">
                    {{ ownMedians.debtToAssets.value }}
                </td>
                <td v-for="rival in rivals" :key="`rival-value-${rival.ticker}`" :class="getRivalClass(rival.ticker, 'debtToAssets')" class="font-weight-bolder">
                    {{ rival.debtToAssets.value }}
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ ownMedians.cashToDebt.name }}</td>
                <td :class="getClass(ownScores.cashToDebt)" class="font-weight-bolder">
                    {{ ownMedians.cashToDebt.value }}
                </td>
                <td v-for="rival in rivals" :key="`rival-value-${rival.ticker}`" :class="getRivalClass(rival.ticker, 'cashToDebt')" class="font-weight-bolder">
                    {{ rival.cashToDebt.value }}
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ ownMedians.interestToOperatingProfit.name }}</td>
                <td :class="getClass(ownScores.interestToOperatingProfit)" class="font-weight-bolder">
                    {{ ownMedians.interestToOperatingProfit.value }}
                </td>
                <td v-for="rival in rivals" :key="`rival-value-${rival.ticker}`" :class="getRivalClass(rival.ticker, 'interestToOperatingProfit')" class="font-weight-bolder">
                    {{ rival.interestToOperatingProfit.value }}
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ ownMedians.interestCoverageRatio.name }}</td>
                <td :class="getClass(ownScores.interestCoverageRatio)" class="font-weight-bolder">
                    {{ ownMedians.interestCoverageRatio.value }}
                </td>
                <td v-for="rival in rivals" :key="`rival-value-${rival.ticker}`" :class="getRivalClass(rival.ticker, 'interestCoverageRatio')" class="font-weight-bolder">
                    {{ rival.interestCoverageRatio.value }}
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ ownMedians.longTermDebtToEbitda.name }}</td>
                <td :class="getClass(ownScores.longTermDebtToEbitda)" class="font-weight-bolder">
                    {{ ownMedians.longTermDebtToEbitda.value }}
                </td>
                <td v-for="rival in rivals" :key="`rival-value-${rival.ticker}`" :class="getRivalClass(rival.ticker, 'longTermDebtToEbitda')" class="font-weight-bolder">
                    {{ rival.longTermDebtToEbitda.value }}
                </td>
                <td></td>
            </tr>

            <tr class="category">
                <td class="font-weight-bold text-left">Performance</td>
                <td :colspan="numberOfColumns - 1"></td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ ownMedians.roic.name }}</td>
                <td :class="getClass(ownScores.roic)" class="font-weight-bolder">
                    {{ ownMedians.roic.value }}
                </td>
                <td v-for="rival in rivals" :key="`rival-value-${rival.ticker}`" :class="getRivalClass(rival.ticker, 'roic')" class="font-weight-bolder">
                    {{ rival.roic.value }}
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ ownMedians.croic.name }}</td>
                <td :class="getClass(ownScores.croic)" class="font-weight-bolder">
                    {{ ownMedians.croic.value }}
                </td>
                <td v-for="rival in rivals" :key="`rival-value-${rival.ticker}`" :class="getRivalClass(rival.ticker, 'croic')" class="font-weight-bolder">
                    {{ rival.croic.value }}
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ ownMedians.roe.name }}</td>
                <td :class="getClass(ownScores.roe)" class="font-weight-bolder">
                    {{ ownMedians.roe.value }}
                </td>
                <td v-for="rival in rivals" :key="`rival-value-${rival.ticker}`" :class="getRivalClass(rival.ticker, 'roe')" class="font-weight-bolder">
                    {{ rival.roe.value }}
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ ownMedians.roa.name }}</td>
                <td :class="getClass(ownScores.roa)" class="font-weight-bolder">
                    {{ ownMedians.roa.value }}
                </td>
                <td v-for="rival in rivals" :key="`rival-value-${rival.ticker}`" :class="getRivalClass(rival.ticker, 'roa')" class="font-weight-bolder">
                    {{ rival.roa.value }}
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ ownMedians.rota.name }}</td>
                <td :class="getClass(ownScores.rota)" class="font-weight-bolder">
                    {{ ownMedians.rota.value }}
                </td>
                <td v-for="rival in rivals" :key="`rival-value-${rival.ticker}`" :class="getRivalClass(rival.ticker, 'rota')" class="font-weight-bolder">
                    {{ rival.rota.value }}
                </td>
                <td></td>
            </tr>

            <tr class="category">
                <td class="font-weight-bold text-left">Cash Flow</td>
                <td :colspan="numberOfColumns - 1"></td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ ownMedians.payoutRatio.name }}</td>
                <td :class="getClass(ownScores.payoutRatio)" class="font-weight-bolder">
                    {{ ownMedians.payoutRatio.value }}
                </td>
                <td v-for="rival in rivals" :key="`rival-value-${rival.ticker}`" :class="getRivalClass(rival.ticker, 'payoutRatio')" class="font-weight-bolder">
                    {{ rival.payoutRatio.value }}
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ ownMedians.freeCashFlowToRevenue.name }}</td>
                <td :class="getClass(ownScores.freeCashFlowToRevenue)" class="font-weight-bolder">
                    {{ ownMedians.freeCashFlowToRevenue.value }}
                </td>
                <td v-for="rival in rivals" :key="`rival-value-${rival.ticker}`" :class="getRivalClass(rival.ticker, 'freeCashFlowToRevenue')" class="font-weight-bolder">
                    {{ rival.freeCashFlowToRevenue.value }}
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ ownMedians.operatingCashFlowMargin.name }}</td>
                <td :class="getClass(ownScores.operatingCashFlowMargin)" class="font-weight-bolder">
                    {{ ownMedians.operatingCashFlowMargin.value }}
                </td>
                <td v-for="rival in rivals" :key="`rival-value-${rival.ticker}`" :class="getRivalClass(rival.ticker, 'operatingCashFlowMargin')" class="font-weight-bolder">
                    {{ rival.operatingCashFlowMargin.value }}
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ ownMedians.capexAsPercentOfRevenue.name }}</td>
                <td :class="getClass(ownScores.capexAsPercentOfRevenue)" class="font-weight-bolder">
                    {{ ownMedians.capexAsPercentOfRevenue.value }}
                </td>
                <td v-for="rival in rivals" :key="`rival-value-${rival.ticker}`" :class="getRivalClass(rival.ticker, 'capexAsPercentOfRevenue')" class="font-weight-bolder">
                    {{ rival.capexAsPercentOfRevenue.value }}
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td class="text-left">{{ ownMedians.capexAsPercentOfOperatingCashFlow.name }}</td>
                <td :class="getClass(ownScores.capexAsPercentOfOperatingCashFlow)" class="font-weight-bolder">
                    {{ ownMedians.capexAsPercentOfOperatingCashFlow.value }}
                </td>
                <td v-for="rival in rivals" :key="`rival-value-${rival.ticker}`" :class="getRivalClass(rival.ticker, 'capexAsPercentOfOperatingCashFlow')" class="font-weight-bolder">
                    {{ rival.capexAsPercentOfOperatingCashFlow.value }}
                </td>
                <td></td>
            </tr>
            </tbody>
        </table>
    </div>
    <CompanySelector @companySelected="onCompanySelected" :show="showModal" @close="showModal = false" />
</div>
</template>

<script lang="ts">
import CompanySelector from '@/components/Company/Modals/CompanySelector.vue';
import ScoreService from '@/services/ScoreService';
import { ref, computed, Ref, PropType, onMounted } from 'vue';
import { PropsWithCompany } from '@/models/Props';
import * as Score from '@/models/Score';
import MetricsService from '@/services/MetricsService';
import { MetricsMedian } from '@/models/Metric';
import { Company } from '@/models/Company/Company';
import { Peer } from '@/models/Company/Peer';

export default {
    name: 'CompanyComparisionBoard',
    components: { CompanySelector },
    props: {
        company: {
            type: Object as PropType<Company>,
            required: true,
            validator: (company: Company) => !!company.ticker
        }
    },

    setup(props: PropsWithCompany) {
        const showModal: Ref<boolean> = ref(false);

        const rivals: Ref<MetricsMedian[]> = ref([]);
        const rivalsScores: Ref<Score.Scores[]> = ref([]);
        const ownMedians: Ref<MetricsMedian> = ref({} as MetricsMedian);
        const ownScores: Ref<Score.Scores> = ref({} as Score.Scores);

        const scoreService = new ScoreService;
        const metricsService = new MetricsService;

        const fetch = async () => {
            const result = await Promise.all([
                metricsService.getMediansForCompany(props.company),
                scoreService.getScores(props.company)
            ]);

            ownMedians.value = result[0];
            ownScores.value = result[1];
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
        }

        const getRivalClass = (ticker: string, metric: string): string => {
            const score = rivalsScores.value
                .find((score: Score.Scores) => score.ticker === ticker);

            if (!score) {
                return '';
            }

            return getClass(score[metric] as number);
        };

        const visiblePeers = computed(() => {
            const rivalTickers = rivals.value.map((score: MetricsMedian) => score.ticker);
            return props.company.peers?.filter((peer: Peer) => !rivalTickers.includes(peer.ticker));
        });

        const numberOfColumns = computed(() => {
            return 4 + rivals.value.length;
        });

        const isRivalsIncludeTicker = (ticker: string): boolean =>
            rivals.value
                .map((rival: MetricsMedian) => rival.ticker)
                .includes(ticker);

        const onCompanySelected = async (company: Company) => {
            if (isRivalsIncludeTicker(company.ticker)) {
                return;
            }

            if (company.ticker === props.company.ticker) {
                return;
            }

            const values = await Promise.all([
                metricsService.getMediansForCompany(company),
                scoreService.getScores(company)
            ]);

            rivals.value.push(values[0]);
            rivalsScores.value.push(values[1]);
        };

        const onRemoveRivalClick = (ticker: string) => {
            rivals.value = rivals.value.filter((rival: MetricsMedian) => rival.ticker !== ticker);
            rivalsScores.value = rivalsScores.value.filter((rivalScore: Score.Scores) => rivalScore.ticker !== ticker);
        };

        const onAddRivalClick = () => {
            showModal.value = true;
        };

        return {
            ownMedians,
            ownScores,
            getClass,
            showModal,
            onAddRivalClick,
            rivals,
            rivalsScores,
            onCompanySelected,
            onRemoveRivalClick,
            getRivalClass,
            numberOfColumns,
            visiblePeers
        };
    }
}
</script>
<style lang="scss" scoped>
a.add-benchmark {
    font-size: 19px;
}
tr.category {
    td {
       font-size: 14px;
       border-bottom: 2px solid #444 !important;
    }
}
.peers {
    font-size: 14px;
}
</style>
