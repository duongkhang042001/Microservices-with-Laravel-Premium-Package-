<template>
<div v-if="balanceSheet.totalAssets && Object.keys(balanceSheet.totalAssets).lentgh !== 0" class="container">
    <div class="table-responsive box">
        <table class="table light-hover">
            <thead>
            <tr class="header-row">
                <th></th>
                <th class="font-weight-bold" v-for="year in years" :key="`header-${year}`">{{ year }}</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-left highlighted">{{ balanceSheet.currentCash.name }}</td>
                    <td v-for="year in years" :key="`currentCash-${year}`" class="highlighted" :class="balanceSheet.currentCash.data[year].classes">
                        {{ balanceSheet.currentCash.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left highlighted">{{ balanceSheet.cash.name }}</td>
                    <td v-for="year in years" :key="`cash-${year}`" class="highlighted" :class="balanceSheet.cash.data[year].classes">
                        {{ balanceSheet.cash.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">{{ balanceSheet.totalCurrentAssets.name }}</td>
                    <td v-for="year in years" :key="`totalCurrentAssets-${year}`" :class="balanceSheet.totalCurrentAssets.data[year].classes">
                        {{ balanceSheet.totalCurrentAssets.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left highlighted">{{ balanceSheet.totalAssets.name }}</td>
                    <td v-for="year in years" :key="`totalAssets-${year}`" class="highlighted" :class="balanceSheet.totalAssets.data[year].classes">
                        {{ balanceSheet.totalAssets.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">{{ balanceSheet.currentPortionOfLongTermDebt.name }}</td>
                    <td v-for="year in years" :key="`currentPortionOfLongTermDebt-${year}`" :class="balanceSheet.currentPortionOfLongTermDebt.data[year].classes">
                        {{ balanceSheet.currentPortionOfLongTermDebt.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">{{ balanceSheet.totalCurrentLiabilities.name }}</td>
                    <td v-for="year in years" :key="`totalCurrentLiabilities-${year}`" :class="balanceSheet.totalCurrentLiabilities.data[year].classes">
                        {{ balanceSheet.totalCurrentLiabilities.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left highlighted">{{ balanceSheet.longTermDebt.name }}</td>
                    <td v-for="year in years" :key="`longTermDebt-${year}`" class="highlighted" :class="balanceSheet.longTermDebt.data[year].classes">
                        {{ balanceSheet.longTermDebt.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">{{ balanceSheet.totalLiabilities.name }}</td>
                    <td v-for="year in years" :key="`totalLiabilities-${year}`" :class="balanceSheet.totalLiabilities.data[year].classes">
                        {{ balanceSheet.totalLiabilities.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left highlighted">{{ balanceSheet.totalEquity.name }}</td>
                    <td v-for="year in years" :key="`totalEquity-${year}`" class="highlighted" :class="balanceSheet.totalEquity.data[year].classes">
                        {{ balanceSheet.totalEquity.data[year].formattedValue }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div v-if="charts.size !== 0">
        <Chart
            v-for="key of charts.keys()"
            :key="`chart-${key}`"
            :type="charts.get(key).type"
            :normalizer="charts.get(key).normalizer"
            :chart-data="charts.get(key).data"
            :init-options="charts.get(key).config"
        ></Chart>
    </div>
</div>
</template>

<script lang="ts">
import CompanyService from '@/services/CompanyService';
import ChartService from '@/services/ChartService';
import { onMounted, PropType, Ref, ref } from 'vue';
import { BalanceSheet, FinancialStatementType } from '@/models/Financial';
import Chart from '@/components/Chart/Chart.vue';
import { ChartGroup } from '@/models/Chart';
import { PropsWithCompany } from '@/models/Props';
import { Company } from '@/models/Company/Company';

export default {
    name: 'CompanyBalanceSheet',
    components: { Chart },
    props: {
        company: {
            type: Object as PropType<Company>,
            reqiured: true,
            validator: (company: Company) => !!company.ticker
        }
    },

    setup(props: PropsWithCompany) {
        const balanceSheet: Ref<BalanceSheet> = ref({} as BalanceSheet);
        const years: Ref<Array<string>> = ref([]);
        const charts: Ref<ChartGroup> = ref(new Map);

        const companyService = new CompanyService;
        const chartService = new ChartService;

        const fetch = async () => {
            balanceSheet.value = await companyService.getBalanceSheet(props.company);
            years.value = Object.keys(balanceSheet.value.totalAssets.data);

            try {
                charts.value = await chartService.getChartGroup(props.company, FinancialStatementType.BalanceSheet);
            } catch (err) {
                console.log(err);
            }
        };

        onMounted(async () => {
            await fetch();

            ChartService.setCanvasDimensions();
        });

        return {
            balanceSheet,
            charts,
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
</style>
