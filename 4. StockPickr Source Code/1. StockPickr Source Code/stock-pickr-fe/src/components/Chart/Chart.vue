<template>
    <div class="row justify-content-center mt-5 mb-5">
        <div class="col-12">
            <component :is="chartComponent" :chart-data="chartData" :options="options"></component>
        </div>
    </div>
</template>

<script>
import ChartService from '@/services/ChartService';
import { upperFirst } from 'lodash';
import BarChart from '@/components/Chart/BarChart';
import LineChart from '@/components/Chart/LineChart';
import { computed, ref } from 'vue';
import { ChartType } from '@/models/Chart';

export default {
    name: 'Chart',
    components: { BarChart, LineChart },
    props: {
        chartData: {
            type: Object,
            default: null
        },
        initOptions: {
            type: Object,
            default: null
        },
        type: {
            type: String,
            required: true,
            validator: (type) => type === ChartType.BAR || type === ChartType.LINE
        },
        normalizer: {
            type: String,
            required: true
        }
    },

    setup(props) {
        const chartComponent = computed(() => {
            return `${upperFirst(props.type)}Chart`;
        });

        const options = ref({
            ...props.initOptions,
            legend: {
                labels: {
                    fontColor: "rgba(255, 255, 255, 0.4)",
                    fontSize: 15,
                }
            },
            scales: {
                yAxes: [
                    {
                        ticks: {
                            fontColor: "rgba(255, 255, 255, 0.4)",
                            fontSize: 15,
                            beginAtZero: true,
                            callback: (label) => {
                                return ChartService.format(label, 1, props.normalizer);
                            },
                        }
                    }
                ],
                xAxes: [
                    {
                        ticks: {
                            fontColor: "rgba(255, 255, 255, 0.4)",
                            fontSize: 15,
                        }
                    }
                ]
            },
            tooltips: {
                callbacks: {
                    label: (tooltipItem, data) => {
                        const label = data['datasets'][tooltipItem.datasetIndex]['label'];
                        const value = ChartService.format(
                            data['datasets'][tooltipItem.datasetIndex]['data'][tooltipItem['index']],
                            2,
                            props.normalizer
                        );

                        return `${label}: ${value}`;
                    },
                }
            }
        });

        return {
            options,
            chartComponent
        };
    },
}
</script>
