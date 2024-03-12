<template>
<section class="section default-bg-color h-100vh">
    <div class="container">
        <div class="row justify-content-center mt-2">
            <div class="col-auto">
                <h2 class="text-center">Leaderboard of <span class="font-iceland">{{ countOfAllCompanies }}</span> companies</h2>
            </div>
        </div>

        <div class="row justify-content-center mb-1 mt-2">
            <div class="col-auto">
                <CompanySearch @search-term-was-changed="search($event)" />
            </div>
        </div>

        <div class="row align-items-center justify-content-center pt-2 pl-3">
            <div v-for="company in companies" :key="company.ticker" class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2 pb-3 pr-3 pl-0 d-flex align-items-center justify-content-center text-center">
                <CompanyCard :company="company" />
            </div>
        </div>
    </div>
</section>
</template>

<script lang="ts">
import { onMounted, Ref, ref } from 'vue'
import { debounce } from 'lodash';
import LeaderboardService from '@/services/LeaderboardService';
import CompanyService from '@/services/CompanyService';
import CompanySearch from '@/components/Company/Search.vue';
import CompanyCard from '@/components/Company/Card.vue';
import { Company, LeaderboardCompany } from '@/models/Company';

export default {
    name: 'Leaderboard',
    components: { CompanySearch, CompanyCard },
    setup() {
        const companies: Ref<LeaderboardCompany[]> = ref([]);
        const countOfAllCompanies: Ref<number> = ref(0);

        const searchTermCache: { [key: string]: Array<LeaderboardCompany> } = {};
        const leaderboardService = new LeaderboardService;
        const companyService = new CompanyService;
        let initCompanies: Array<LeaderboardCompany> = [];

        const fetchCompanies = async () => {
            const pages = [1, 2, 3, 4];
            for (const page of pages) {
                const companiesForPage: Array<LeaderboardCompany> = await leaderboardService.get(12, page - 1);
                companies.value = [...companies.value, ...companiesForPage];
            }

            initCompanies = companies.value;
        };

        const search = debounce(async function(searchTerm: string) {
            if (!searchTerm) {
                companies.value = initCompanies;
                return;
            }

            if (searchTermCache[searchTerm]) {
                companies.value = searchTermCache[searchTerm];
                return;
            }

            companies.value = await companyService.search<LeaderboardCompany>(searchTerm);
            searchTermCache[searchTerm] = companies.value;
        }, 500);

        onMounted(async () => {
            countOfAllCompanies.value = await companyService.getCount();
            await fetchCompanies();
        })


        return {
            companies,
            search,
            countOfAllCompanies
        };
    }
}
</script>
<style lang="scss" scoped>
    p.total {
        color: rgba(255, 255, 255, 0.6);
    }
    h2 {
        color: rgba(255, 255, 255, 0.8);
        letter-spacing: 4px;
    }
    h2 span {
        font-size: 4rem;
        color: white;
    }
</style>
