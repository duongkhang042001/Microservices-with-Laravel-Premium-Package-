<script>
import { Line } from 'vue3-chart-v2';

export default {
    extends: Line,
    name: 'LineChart',
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
