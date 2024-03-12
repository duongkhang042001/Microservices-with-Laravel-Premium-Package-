<template>
<div v-if="cashFlow.operatingCashFlow && Object.keys(cashFlow.operatingCashFlow).lentgh !== 0" class="container">
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
                    <td class="text-left">{{ cashFlow.netIncome.name }}</td>
                    <td v-for="year in years" :key="`netIncome-${year}`" :class="cashFlow.netIncome.data[year].classes">
                        {{ cashFlow.netIncome.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">{{ cashFlow.deprecationAmortization.name }}</td>
                    <td v-for="year in years" :key="`deprecationAmortization-${year}`" :class="cashFlow.deprecationAmortization.data[year].classes">
                        {{ cashFlow.deprecationAmortization.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left highlighted">{{ cashFlow.operatingCashFlow.name }}</td>
                    <td v-for="year in years" :key="`operatingCashFlow-${year}`" class="highlighted" :class="cashFlow.operatingCashFlow.data[year].classes">
                        {{ cashFlow.operatingCashFlow.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">{{ cashFlow.capex.name }}</td>
                    <td v-for="year in years" :key="`capex-${year}`" :class="cashFlow.capex.data[year].classes">
                        {{ cashFlow.capex.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left highlighted">{{ cashFlow.freeCashFlow.name }}</td>
                    <td v-for="year in years" :key="`freeCashFlow-${year}`" class="highlighted" :class="cashFlow.freeCashFlow.data[year].classes">
                        {{ cashFlow.freeCashFlow.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">{{ cashFlow.cashFromFinancing.name }}</td>
                    <td v-for="year in years" :key="`cashFromFinancing-${year}`" :class="cashFlow.cashFromFinancing.data[year].classes">
                        {{ cashFlow.cashFromFinancing.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">{{ cashFlow.cashFromInvesting.name }}</td>
                    <td v-for="year in years" :key="`cashFromInvesting-${year}`" :class="cashFlow.cashFromInvesting.data[year].classes">
                        {{ cashFlow.cashFromInvesting.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">{{ cashFlow.cashDividendPaid.name }}</td>
                    <td v-for="year in years" :key="`cashDividendPaid-${year}`" :class="cashFlow.cashDividendPaid.data[year].classes">
                        {{ cashFlow.cashDividendPaid.data[year].formattedValue }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div v-if="charts.size !== 0">
        <Chart
            v-for="key of charts?.keys()"
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
import { CashFlow, FinancialStatementType } from '@/models/Financial';
import Chart from '@/components/Chart/Chart.vue';
import { ChartGroup } from '@/models/Chart';
import { PropsWithCompany } from '@/models/Props';
import { Company } from '@/models/Company/Company';

export default {
    name: 'CompanyCashFlow',
    components: { Chart },
    props: {
        company: {
            type: Object as PropType<Company>,
            reqiured: true,
            validator: (company: Company) => !!company.ticker
        }
    },

    setup(props: PropsWithCompany) {
        const cashFlow: Ref<CashFlow> = ref({} as CashFlow);
        const years: Ref<Array<string>> = ref([]);
        const charts: Ref<ChartGroup> = ref(new Map);

        const companyService = new CompanyService;
        const chartService = new ChartService;

        const fetch = async () => {
            cashFlow.value = await companyService.getCashFlow(props.company);
            years.value = Object.keys(cashFlow.value.operatingCashFlow.data);

            try {
                charts.value = await chartService.getChartGroup(props.company, FinancialStatementType.CashFlow);
            } catch (err) {
                console.log(err);
            }
        };

        onMounted(async () => {
            await fetch();

            ChartService.setCanvasDimensions();
        });

        return {
            cashFlow,
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
