<script>
import { Bar } from 'vue3-chart-v2';

export default {
    extends: Bar,
    name: 'BarChart',
    props: {
        chartData: {
            type: Object,
            default: null
        },
        options: {
            type: Object,
            default: null
        }
    },

    mounted() {
        this.renderChart(this.chartData, this.options);
    }
}
</script>
