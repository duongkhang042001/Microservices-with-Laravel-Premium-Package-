<template>
    <router-link :to="{ name: 'company-profile', params: { ticker: company.ticker } }" class="card">
        <div class="card-body d-flex flex-column white-hover">
            <h5 class="card-title font-wallpoet">{{ company.ticker }}</h5>
            <p class="card-subtitle mb-2 text-muted" data-toggle="tooltip" data-placement="bottom" :title="company.name">{{ company.name }}</p>

            <div class="row mt-3 font-iceland number">
                <div class="col-12">
                    #{{ company.position }}
                </div>
            </div>
            <div class="row mt-auto font-iceland number">
                <div class="col-6" data-toggle="tooltip" data-placement="top" :title="getTotalScoresTooltip()">
                    {{ company.totalScores }}
                </div>
                <div class="col-6" data-toggle="tooltip" data-placement="top" title="Total scores as a percentage of total possible scores">
                    {{ company.totalScorePercent }}
                </div>
            </div>
        </div>
    </router-link>
</template>

<script lang="ts">
import { PropType } from 'vue';
import { PropsWithCompany } from '@/models/Props';
import { Company } from '@/models/Company/Company';

export default {
    name: 'CompanyCard',
    props: {
        company: {
            type: Object as PropType<Company>,
            required: true,
            validator: (company: Company) => !!company.ticker
        }
    },

    setup(props: PropsWithCompany) {
        const getCompanyLink = () => {
            return `/companies/${props.company.ticker}`;
        };

        const getTotalScoresTooltip = () => {
            return `Total scores for ${props.company.ticker}`;
        };

        return {
            getCompanyLink,
            getTotalScoresTooltip
        }
    }
}
</script>

<style lang="scss" scoped>
    a.card {
        width: 18rem;
        background: rgba(39, 42, 51, 0.7) !important;
    }
    a.card:hover {
        text-decoration: none;
        background: rgba(39, 42, 51, 0.4) !important;
    }
    .card-body {
        height: 165px !important;
        cursor: pointer;
        color: rgba(255, 255, 255, 0.7);
    }
    .card-subtitle {
        font-size: 13px;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
        line-height: 1;
    }
    .card-title {
        font-size: 20px;
    }
    .number {
        font-size: 19px;
    }
</style>
