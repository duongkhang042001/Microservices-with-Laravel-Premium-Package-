<template>
    <div v-if="marketData?.analyst?.rating?.buy">
        <div class="row justify-content-center box mt-4 pb-2">
            <div class="col-12">
            </div>
            <div class="col-sm-6">
                <h4 class="text-center">Analyst Recommendations</h4>
                <table class="table">
                    <tbody>
                    <tr>
                        <td class="text-left">Buy</td>
                        <td class="text-right">{{ marketData.analyst.rating.buy }}</td>
                    </tr>
                    <tr>
                        <td class="text-left">Hold</td>
                        <td class="text-right">{{ marketData.analyst.rating.hold }}</td>
                    </tr>
                    <tr>
                        <td class="text-left">Sell</td>
                        <td class="text-right">{{ marketData.analyst.rating.sell }}</td>
                    </tr>
                    <tr>
                        <td class="text-left">Price Target Average</td>
                        <td class="text-right">{{ marketData.analyst.priceTarget.average.formatted }}</td>
                    </tr>
                    <tr>
                        <td class="text-left">Price Target High</td>
                        <td class="text-right">{{ marketData.analyst.priceTarget.high.formatted }}</td>
                    </tr>
                    <tr>
                        <td class="text-left">Price Target Low</td>
                        <td class="text-right">{{ marketData.analyst.priceTarget.low.formatted }}</td>
                    </tr>
                    <tr v-if="marketData.analyst.numberOfAnalysts">
                        <td class="text-left">Number of analysts for price target</td>
                        <td class="text-right">{{ marketData.analyst.numberOfAnalysts }}</td>
                    </tr>
                    <tr>
                        <td class="text-left">Rating Date</td>
                        <td class="text-right">{{ marketData.analyst.rating.date }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row justify-content-center box mt-4 pb-4">
            <div class="col-12">
                <iframe width="100%" frameborder="0" height="400" :src="recommendationWidgetUrl"></iframe>
                <iframe width="100%" frameborder="0" height="400" :src="epsEstimationWidgetUrl"></iframe>
                <iframe width="100%" frameborder="0" height="400" :src="epsSupriseWidgetUrl"></iframe>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { PropType } from 'vue';
import MarketData from '@/models/MarketData/MarketData';
import { MarketDataProps } from '@/models/Props';
export default {
    name: 'CompanyAnalyst',
    props: {
        marketData: {
            type: Object as PropType<MarketData>,
            required: true,
            validator: (marketData: MarketData) => !!marketData.shareData && !!marketData.analyst
        }
    },

    setup(props: MarketDataProps) {
        const recommendationWidgetUrl = `https://widget.finnhub.io/widgets/recommendation?symbol=${props.marketData.ticker}`;
        const epsEstimationWidgetUrl = `https://widget.finnhub.io/widgets/eps-estimate?symbol=${props.marketData.ticker}`;
        const epsSupriseWidgetUrl = `https://widget.finnhub.io/widgets/historical-eps?symbol=${props.marketData.ticker}`;

        return {
            recommendationWidgetUrl,
            epsEstimationWidgetUrl,
            epsSupriseWidgetUrl
        };
    }
}
</script>

<style lang="scss" scoped>
iframe {
    margin-top: 30px;
}
table {
    margin-bottom: 0 !important;
}
</style>
