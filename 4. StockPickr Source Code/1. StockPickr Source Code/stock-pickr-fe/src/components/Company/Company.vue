<template>
<section class="section default-bg-color">
    <div v-if="company.ticker && marketData.shareData">
        <nav class="company-nav navbar navbar-expand-lg navbar-dark mt-2 mb-2">
            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <ul class="navbar-nav mr-auto" role="tablist">
                    <li v-for="panel in panels" class="nav-item" :key="panel.route">
                        <router-link :to="
                            {
                                name: panel.route,
                                params: {
                                    ticker: company.ticker
                                }
                            }
                        ">
                            {{ panel.name }}
                        </router-link>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="h-100vh">
            <transition name="slide-fade" mode="out-in">
                <router-view
                    v-bind="{company: company, marketData: marketData}"
                    :key="$route.path"
                    v-slot="{ Component }">

                        <component :is="Component" />
                </router-view>
            </transition>
        </div>
    </div>
</section>
</template>

<script lang="ts">
import CompanyService from '@/services/CompanyService';
import MarketDataService from '@/services/MarketDataService';
import { onMounted, ref, Ref } from 'vue';
import { PropsWithTicker } from '@/models/Props';
import { Company } from '@/models/Company/Company';
import MarketData from '@/models/MarketData/MarketData';

export default {
    name: 'Company',
    props: {
        ticker: {
            type: String,
            required: true
        }
    },

    setup(props: PropsWithTicker) {
        const panels: Ref<Array<object>> = ref([
            { name: 'Profile',          route: 'company-profile'},
            { name: 'Charts',           route: 'company-charts'},
            { name: 'Metrics',          route: 'company-metrics'},
            { name: 'Income Statement', route: 'company-income-statement'},
            { name: 'Balance Sheet',    route: 'company-balance-sheet'},
            { name: 'Cash Flow',        route: 'company-cash-flow'},
            { name: 'Scores',           route: 'company-scores'},
            { name: 'vs Sector',        route: 'company-sector-scores'},
            { name: 'vs Companies',     route: 'company-comparision'},
        ]);

        const company: Ref<Company> = ref({} as Company);
        const marketData: Ref<MarketData> = ref({} as MarketData);

        const companyService = new CompanyService;
        const marketDataService = new MarketDataService;

        const fetch = async () => {
            company.value = await companyService.getByTicker(props.ticker as string);
            marketData.value = await marketDataService.getMarketData(company.value);
        };

        onMounted(async () => {
            await fetch();
            companyService.log(CompanyService.logView, (company.value.ticker as string));
        });

        return {
            panels,
            company,
            marketData
        };
    }
}
</script>
<style lang="scss">
.router-link-exact-active {
    border-bottom: 3px #007053 solid !important;
    color: white !important;
}

tr.no-hover:hover {
    background-color: unset !important;
    color: unset !important;
}
td {
    border: none !important;
}
h4 {
    margin-top: 10px !important;
}
.text-danger {
    color: #ff4495 !important;
}
.text-warning {
    color: #f39c12 !important;
}
.text-success {
    color: #008c31 !important;
}
table.scores {
    font-size: 12px;
    color: rgba(255, 255, 255, 0.6);
}
table.scores th {
    font-size: 14px;
    color: white;
}
table.scores tr.summary {
    font-size: 14px;
    color: white;
}

.company-nav {
    padding: 15px 0;
    width: 100%;
    border-radius: 0;
    margin-bottom: 0;
    transition: all .5s ease-in-out;
    background-color: transparent;
}

.company-nav ul li a {
    color: rgba(255, 255, 255, .6);
    font-size: 15px;
    background-color: transparent !important;
    padding: 5px 0;
    margin: 0 7px;
    transition: color .4s;
}

.company-nav ul li.active a,
.company-nav ul li a:hover,
.company-nav ul li a:active {
    border-bottom: 3px #007053 solid !important;
    color: white !important;
}

.company-nav .btn-custom {
    margin-top: 5px;
    margin-bottom: 5px
}

.company-nav .navbar-brand.logo img {
    height: 32px
}
</style>
