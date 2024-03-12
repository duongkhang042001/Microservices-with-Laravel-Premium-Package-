<template>
<div v-if="incomeStatement.totalRevenue && Object.keys(incomeStatement.totalRevenue).lentgh !== 0" class="container">
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
                    <td class="text-left highlighted">{{ incomeStatement.totalRevenue.name }}</td>
                    <td v-for="year in years" :key="`totalRevenue-${year}`" class="highlighted" :class="incomeStatement.totalRevenue.data[year].classes">
                        {{ incomeStatement.totalRevenue.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">{{ incomeStatement.costOfRevenue.name }}</td>
                    <td v-for="year in years" :key="`costOfRevenue-${year}`" :class="incomeStatement.costOfRevenue.data[year].classes">
                        {{ incomeStatement.costOfRevenue.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left highlighted">{{ incomeStatement.grossProfit.name }}</td>
                    <td v-for="year in years" :key="`grossProfit-${year}`" class="highlighted" :class="incomeStatement.grossProfit.data[year].classes">
                        {{ incomeStatement.grossProfit.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">{{ incomeStatement.operatingIncome.name }}</td>
                    <td v-for="year in years" :key="`operatingIncome-${year}`" :class="incomeStatement.operatingIncome.data[year].classes">
                        {{ incomeStatement.operatingIncome.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">{{ incomeStatement.pretaxIncome.name }}</td>
                    <td v-for="year in years" :key="`pretaxIncome-${year}`" :class="incomeStatement.pretaxIncome.data[year].classes">
                        {{ incomeStatement.pretaxIncome.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">{{ incomeStatement.incomeTax.name }}</td>
                    <td v-for="year in years" :key="`incomeTax-${year}`" :class="incomeStatement.incomeTax.data[year].classes">
                        {{ incomeStatement.incomeTax.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">{{ incomeStatement.interestExpense.name }}</td>
                    <td v-for="year in years" :key="`interestExpense-${year}`" :class="incomeStatement.interestExpense.data[year].classes">
                        {{ incomeStatement.interestExpense.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">{{ incomeStatement.researchAndDevelopment.name }}</td>
                    <td v-for="year in years" :key="`researchAndDevelopment-${year}`" :class="incomeStatement.researchAndDevelopment.data[year].classes">
                        {{ incomeStatement.researchAndDevelopment.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">{{ incomeStatement.sellingGeneralAdministrative.name }}</td>
                    <td v-for="year in years" :key="`sellingGeneralAdministrative-${year}`" :class="incomeStatement.sellingGeneralAdministrative.data[year].classes">
                        {{ incomeStatement.sellingGeneralAdministrative.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">{{ incomeStatement.netIncome.name }}</td>
                    <td v-for="year in years" :key="`netIncome-${year}`" :class="incomeStatement.netIncome.data[year].classes">
                        {{ incomeStatement.netIncome.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left highlighted">{{ incomeStatement.ebit.name }}</td>
                    <td v-for="year in years" :key="`ebit-${year}`" class="highlighted" :class="incomeStatement.ebit.data[year].classes">
                        {{ incomeStatement.ebit.data[year].formattedValue }}
                    </td>
                </tr>
                <tr>
                    <td class="text-left">{{ incomeStatement.eps.name }}</td>
                    <td v-for="year in years" :key="`eps-${year}`" :class="incomeStatement.eps.data[year].classes">
                        {{ incomeStatement.eps.data[year].formattedValue }}
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
import { FinancialStatementType, IncomeStatement } from '@/models/Financial';
import Chart from '@/components/Chart/Chart.vue';
import { ChartGroup } from '@/models/Chart';
import { PropsWithCompany } from '@/models/Props';
import { Company } from '@/models/Company/Company';

export default {
    name: 'CompanyIncomeStatement',
    components: { Chart },
    props: {
        company: {
            type: Object as PropType<Company>,
            reqiured: true,
            validator: (company: Company) => !!company.ticker
        }
    },

    setup(props: PropsWithCompany) {
        const incomeStatement: Ref<IncomeStatement> = ref({} as IncomeStatement);
        const years: Ref<Array<string>> = ref([]);
        const charts: Ref<ChartGroup> = ref(new Map);

        const companyService = new CompanyService;
        const chartService = new ChartService;

        const fetch = async () => {
            incomeStatement.value = await companyService.getIncomeStatement(props.company);
            years.value = Object.keys(incomeStatement.value.totalRevenue.data);

            try {
                charts.value = await chartService.getChartGroup(props.company, FinancialStatementType.IncomeStatement);
            } catch (err) {
                console.log(err);
            }
        };

        onMounted(async () => {
            await fetch();

            ChartService.setCanvasDimensions();
        });

        return {
            incomeStatement,
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
