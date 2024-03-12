<template>
<div class="container">
    <div class="row justify-content-center mt-5 mb-5">
    </div>

    <Chart v-if="charts.get('revenue')" :type="charts.get('revenue').type" :normalizer="charts.get('revenue').normalizer" :chart-data="charts.get('revenue').data" :init-options="charts.get('revenue').config"></Chart>
    <div class="row justify-content-center mt-6 mb-6"></div>

    <Chart v-if="charts.get('income')" :type="charts.get('income').type" :normalizer="charts.get('income').normalizer" :chart-data="charts.get('income').data" :init-options="charts.get('income').config"></Chart>
    <div class="row justify-content-center mt-6 mb-6"></div>

    <Chart v-if="charts.get('margins')" :type="charts.get('margins').type" :normalizer="charts.get('margins').normalizer" :chart-data="charts.get('margins').data" :init-options="charts.get('margins').config"></Chart>
    <div class="row justify-content-center mt-6 mb-6"></div>

    <Chart v-if="charts.get('cashFlow')" :type="charts.get('cashFlow').type" :normalizer="charts.get('cashFlow').normalizer" :chart-data="charts.get('cashFlow').data" :init-options="charts.get('cashFlow').config"></Chart>
    <div class="row justify-content-center mt-6 mb-6"></div>

    <Chart v-if="charts.get('growth')" :type="charts.get('growth').type" :normalizer="charts.get('growth').normalizer" :chart-data="charts.get('growth').data" :init-options="charts.get('growth').config"></Chart>
    <div class="row justify-content-center mt-6 mb-6"></div>

</div>
</template>

<script lang="ts">
import ChartService from '@/services/ChartService';
import { onMounted, PropType, Ref, ref } from 'vue';
import Chart from '@/components/Chart/Chart.vue';
import { Company } from '@/models/Company';
import { ChartGroup } from '@/models/Chart';
import { PropsWithCompany } from '@/models/Props';

export default {
    name: 'CompanyCharts',
    components: { Chart },
    props: {
        company: {
            type: Object as PropType<Company>,
            required: true,
            validator: (company: Company) => !!company.ticker
        }
    },

    setup(props: PropsWithCompany) {
        const charts: Ref<ChartGroup> = ref(new Map);
        const chartService = new ChartService;

        const fetch = async () => {
            charts.value = await chartService.getChartGroup(props.company, 'summary');
        };

        onMounted(async () => {
            await fetch();

            ChartService.setCanvasDimensions();
        });

        return {
            charts
        };
    }
}
</script>
