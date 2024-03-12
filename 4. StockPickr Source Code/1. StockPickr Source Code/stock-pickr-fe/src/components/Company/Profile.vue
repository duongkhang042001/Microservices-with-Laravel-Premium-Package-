<template>
<div v-if="company" class="container">
    <h4>{{ company.fullName }}</h4>
    <h5>{{ marketData?.shareData?.price?.formatted }}</h5>

    <div class="row justify-content-center mt-4 pb-2 box">
        <div class="col-sm-6">
            <h4 class="text-center">Summary</h4>
            <table class="table">
                <tbody>
                <tr>
                    <td class="text-left">Sector</td>
                    <td class="text-right">{{ company.sector.name }}</td>
                </tr>
                <tr>
                    <td class="text-left">Industry</td>
                    <td class="text-right">{{ company.industry }}</td>
                </tr>
                <tr v-if="company.ceo">
                    <td class="text-left">CEO</td>
                    <td class="text-right">{{ company.ceo }}</td>
                </tr>
                <tr v-if="company.employees">
                    <td class="text-left">Employees</td>
                    <td class="text-right">{{ company.employees }}</td>
                </tr>
                <tr v-if="marketData?.shareData?.marketCap">
                    <td class="text-left">Market Cap</td>
                    <td class="text-right">{{ marketData.shareData.marketCap.formatted }}</td>
                </tr>
                <tr v-if="scores?.summary">
                    <td class="text-left">Total Score</td>
                    <td class="text-right">{{ scores?.summary?.totalScorePercent }}</td>
                </tr>
                <tr v-if="scores?.summary">
                    <td class="text-left">Current Position</td>
                    <td class="text-right">
                        <span v-if="companyCount">{{ scores?.summary?.position }} / {{ companyCount }}</span>
                        <span v-else>Loading...</span>
                    </td>
                </tr>
                <tr v-if="scores?.summary">
                    <td class="text-left">Current Percentile</td>
                    <td class="text-right">Top {{ scores?.summary?.positionPercentile }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row justify-content-center mt-4 box">
        <div class="col-12" v-if="company.description">
            <h4>Company Profile</h4>
            <p class="text-justify text-color-gray">{{ company.description }}</p>
        </div>
    </div>

    <CompanyAnalyst v-if="marketData?.analyst?.rating?.buy" :marketData="marketData" :company="company" />
</div>
</template>

<script lang="ts">
import CompanyAnalyst from '@/components/Company/Analyst.vue';
import CompanyService from '@/services/CompanyService';
import { onMounted, PropType, ref, Ref } from 'vue';
import { Company } from '@/models/Company';
import ScoreService from '@/services/ScoreService';
import { Scores } from '@/models/Score';
import { PropsWithCompany } from '@/models/Props';
import MarketData from '@/models/MarketData/MarketData';
import MarketDataService from '@/services/MarketDataService';

export default {
    name: 'CompanyProfile',
    components: { CompanyAnalyst },
    props: {
        company: {
            type: Object as PropType<Company>,
            required: true,
            validator: (company: Company) => !!company.ticker
        }
    },

    setup(props: PropsWithCompany) {
        const companyCount: Ref<number> = ref(0);
        const scores: Ref<Scores> = ref({} as Scores);
        const marketData: Ref<MarketData> = ref({} as MarketData);

        const companyService = new CompanyService;
        const scoreService = new ScoreService;
        const marketDataService = new MarketDataService;

        onMounted(async () => {
            try {
                companyCount.value = await companyService.getCount();
                scores.value = await scoreService.getScores(props.company);
                marketData.value = await marketDataService.getMarketData(props.company);
            } catch (err) {
                console.error(err);
            }
        });

        return {
            companyCount,
            scores,
            marketData
        };
    }
}
</script>
<style lang="scss" scoped>
.text-color-gray {
    color: rgba(255, 255, 255, 0.6);
}
table {
    margin-bottom: 0 !important;
}
</style>
