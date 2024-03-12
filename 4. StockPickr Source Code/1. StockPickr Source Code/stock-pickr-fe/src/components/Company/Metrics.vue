<template>
<div v-if="metrics.currentRatio && Object.keys(metrics.currentRatio).lentgh !== 0" class="container">
    <div class="table-responsive box">
        <table class="table light-hover">
            <thead>
                <tr>
                    <th class="font-weight-bold text-left first-column">Margins</th>
                    <th class="font-weight-bold text-center" v-for="year in years" :key="`header-${year}`">{{ year }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-left highlighted">{{ metrics.grossMargin.name }}</td>
                    <td v-for="year in years" :key="`grossMargin-${year}`" :class="metrics.grossMargin.data[year].classes" class="highlighted">
                        {{ metrics.grossMargin.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">{{ metrics.operatingMargin.name }}</td>
                    <td v-for="year in years" :key="`operatingMargin-${year}`" :class="metrics.operatingMargin.data[year].classes">
                        {{ metrics.operatingMargin.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">{{ metrics.netMargin.name }}</td>
                    <td v-for="year in years" :key="`netMargin-${year}`" :class="metrics.netMargin.data[year].classes">
                        {{ metrics.netMargin.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">{{ metrics.operatingCashFlowMargin.name }}</td>
                    <td v-for="year in years" :key="`operatingCashFlowMargin-${year}`" :class="metrics.operatingCashFlowMargin.data[year].classes">
                        {{ metrics.operatingCashFlowMargin.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">{{ metrics.sgaToGrossProfit.name }}</td>
                    <td v-for="year in years" :key="`sgaToGrossProfit-${year}`" :class="metrics.sgaToGrossProfit.data[year].classes">
                        {{ metrics.sgaToGrossProfit.data[year].formattedValue }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="table-responsive box mt-4">
        <table class="table light-hover">
            <thead>
                <tr>
                    <th class="font-weight-bold text-left first-column">Growth</th>
                    <th class="font-weight-bold text-center" v-for="year in years" :key="`header-${year}`">{{ year }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-left highlighted">{{ metrics.totalRevenueGrowth.name }}</td>
                    <td v-for="year in years" :key="`totalRevenueGrowth-${year}`" :class="metrics.totalRevenueGrowth.data[year].classes" class="highlighted">
                        {{ metrics.totalRevenueGrowth.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">{{ metrics.netIncomeGrowth.name }}</td>
                    <td v-for="year in years" :key="`netIncomeGrowth-${year}`" :class="metrics.netIncomeGrowth.data[year].classes">
                        {{ metrics.netIncomeGrowth.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">{{ metrics.epsGrowth.name }}</td>
                    <td v-for="year in years" :key="`epsGrowth-${year}`" :class="metrics.epsGrowth.data[year].classes">
                        {{ metrics.epsGrowth.data[year].formattedValue }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="table-responsive box mt-4">
        <table class="table light-hover">
            <thead>
                <tr>
                    <th class="font-weight-bold text-left first-column">Liquidity</th>
                    <th class="font-weight-bold" v-for="year in years" :key="`header-${year}`">{{ year }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-left highlighted">{{ metrics.currentRatio.name }}</td>
                    <td v-for="year in years" :key="`currentRatio-${year}`" :class="metrics.currentRatio.data[year].classes" class="highlighted">
                        {{ metrics.currentRatio.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">{{ metrics.quickRatio.name }}</td>
                    <td v-for="year in years" :key="`quickRatio-${year}`" :class="metrics.quickRatio.data[year].classes">
                        {{ metrics.quickRatio.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">{{ metrics.operatingCashFlowToCurrentLiabilities.name }}</td>
                    <td v-for="year in years" :key="`operatingCashFlowToCurrentLiabilities-${year}`" :class="metrics.operatingCashFlowToCurrentLiabilities.data[year].classes">
                        {{ metrics.operatingCashFlowToCurrentLiabilities.data[year].formattedValue }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="table-responsive box mt-4">
        <table class="table light-hover">
            <thead>
                <tr>
                    <th class="font-weight-bold text-left first-column">Solvency</th>
                    <th class="font-weight-bold" v-for="year in years" :key="`header-${year}`">{{ year }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-left highlighted">{{ metrics.cashToDebt.name }}</td>
                    <td v-for="year in years" :key="`cashToDebt-${year}`" :class="metrics.cashToDebt.data[year].classes" class="highlighted">
                        {{ metrics.cashToDebt.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left highlighted">{{ metrics.debtToCapital.name }}</td>
                    <td v-for="year in years" :key="`debtToCapital-${year}`" :class="metrics.debtToCapital.data[year].classes" class="highlighted">
                        {{ metrics.debtToCapital.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">{{ metrics.debtToAssets.name }}</td>
                    <td v-for="year in years" :key="`debtToAssets-${year}`" :class="metrics.debtToAssets.data[year].classes">
                        {{ metrics.debtToAssets.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">{{ metrics.interestCoverageRatio.name }}</td>
                    <td v-for="year in years" :key="`interestCoverageRatio-${year}`" :class="metrics.interestCoverageRatio.data[year].classes">
                        {{ metrics.interestCoverageRatio.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">{{ metrics.longTermDebtToEbitda.name }}</td>
                    <td v-for="year in years" :key="`longTermDebtToEbitda-${year}`" :class="metrics.longTermDebtToEbitda.data[year].classes">
                        {{ metrics.longTermDebtToEbitda.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">{{ metrics.interestToOperatingProfit.name }}</td>
                    <td v-for="year in years" :key="`interestToOperatingProfit-${year}`" :class="metrics.interestToOperatingProfit.data[year].classes">
                        {{ metrics.interestToOperatingProfit.data[year].formattedValue }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="table-responsive box mt-4">
        <table class="table light-hover">
            <thead>
                <tr>
                    <th class="font-weight-bold text-left first-column">Performance</th>
                    <th class="font-weight-bold" v-for="year in years" :key="`header-${year}`">{{ year }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-left highlighted">{{ metrics.roic.name }}</td>
                    <td v-for="year in years" :key="`roic-${year}`" :class="metrics.roic.data[year].classes" class="highlighted">
                        {{ metrics.roic.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">{{ metrics.roe.name }}</td>
                    <td v-for="year in years" :key="`roe-${year}`" :class="metrics.roe.data[year].classes">
                        {{ metrics.roe.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">{{ metrics.roa.name }}</td>
                    <td v-for="year in years" :key="`roa-${year}`" :class="metrics.roa.data[year].classes">
                        {{ metrics.roa.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">{{ metrics.rota.name }}</td>
                    <td v-for="year in years" :key="`rota-${year}`" :class="metrics.rota.data[year].classes">
                        {{ metrics.rota.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">{{ metrics.croic.name }}</td>
                    <td v-for="year in years" :key="`croic-${year}`" :class="metrics.croic.data[year].classes">
                        {{ metrics.croic.data[year].formattedValue }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="table-responsive box mt-4">
        <table class="table light-hover">
            <thead>
                <tr>
                    <th class="font-weight-bold text-left first-column">Cash Flow</th>
                    <th class="font-weight-bold" v-for="year in years" :key="`header-${year}`">{{ year }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-left highlighted">{{ metrics.freeCashFlowToRevenue.name }}</td>
                    <td v-for="year in years" :key="`freeCashFlowToRevenue-${year}`" :class="metrics.freeCashFlowToRevenue.data[year].classes" class="highlighted">
                        {{ metrics.freeCashFlowToRevenue.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">{{ metrics.payoutRatio.name }}</td>
                    <td v-for="year in years" :key="`payoutRatio-${year}`" :class="metrics.payoutRatio.data[year].classes">
                        {{ metrics.payoutRatio.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">{{ metrics.capexAsPercentOfRevenue.name }}</td>
                    <td v-for="year in years" :key="`capexAsPercentOfRevenue-${year}`" :class="metrics.capexAsPercentOfRevenue.data[year].classes">
                        {{ metrics.capexAsPercentOfRevenue.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">{{ metrics.capexAsPercentOfOperatingCashFlow.name }}</td>
                    <td v-for="year in years" :key="`capexAsPercentOfOperatingCashFlow-${year}`" :class="metrics.capexAsPercentOfOperatingCashFlow.data[year].classes">
                        {{ metrics.capexAsPercentOfOperatingCashFlow.data[year].formattedValue }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</template>

<script lang="ts">
import CompanyService from '@/services/CompanyService';
import { onMounted, PropType, Ref, ref } from 'vue';
import { Metrics } from '@/models/Financial';
import { Company } from '@/models/Company';
import { PropsWithCompany } from '@/models/Props';

export default {
    name: 'CompanyMetrics',
    props: {
        company: {
            type: Object as PropType<Company>,
            required: true,
            validator: (company: Company) => !!company.ticker
        }
    },


    setup(props: PropsWithCompany) {
        const metrics: Ref<Metrics> = ref({} as Metrics);
        const years: Ref<Array<string>> = ref([]);
        const companyService = new CompanyService;

        const fetch = async () => {
            metrics.value = await companyService.getMetrics(props.company);
            years.value = Object.keys(metrics.value.currentRatio.data);
        }

        onMounted(async () => {
            await fetch();
        });

        return {
            metrics,
            years
        };
    }
}
</script>
<style lang="scss" scoped>
tr:not(.highlighted) {
    font-size: 12px !important;
    color: rgba(255, 255, 255, 0.6) !important;
}
.first-column {
    // width: 220px !important;
}
table {
    table-layout: fixed !important;
}
</style>
