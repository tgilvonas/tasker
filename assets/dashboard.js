import { createApp, defineComponent, h } from 'vue';
import { Chart, ArcElement, Tooltip, Legend, PieController } from 'chart.js';

Chart.register(PieController, ArcElement, Tooltip, Legend);

const PieChart = defineComponent({
    props: {
        labels: { type: Array, default: () => [] },
        values: { type: Array, default: () => [] },
        colors: { type: Array, default: () => [] },
    },
    data() {
        return {
            chart: null,
        };
    },
    mounted() {
        this.renderChart();
    },
    beforeUnmount() {
        if (this.chart) {
            this.chart.destroy();
        }
    },
    watch: {
        labels: { deep: true, handler() { this.renderChart(); } },
        values: { deep: true, handler() { this.renderChart(); } },
        colors: { deep: true, handler() { this.renderChart(); } },
    },
    methods: {
        renderChart() {
            if (!this.$refs.canvas) {
                return;
            }

            if (this.chart) {
                this.chart.destroy();
            }

            const labels = this.labels.filter((label, index) => Number(this.values[index] ?? 0) > 0);
            const values = this.values.filter((value) => Number(value) > 0);
            const colors = this.colors.filter((color, index) => Number(this.values[index] ?? 0) > 0);

            this.chart = new Chart(this.$refs.canvas.getContext('2d'), {
                type: 'pie',
                data: {
                    labels,
                    datasets: [
                        {
                            data: values,
                            backgroundColor: colors.length ? colors : ['#0d6efd', '#198754', '#6c757d'],
                            borderColor: '#ffffff',
                            borderWidth: 1,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        tooltip: {
                            callbacks: {
                                label: (context) => `${context.label}: ${context.formattedValue}`,
                            },
                        },
                    },
                },
            });
        },
    },
    render() {
        return h('canvas', {
            ref: 'canvas',
            class: 'w-100 h-100',
        });
    },
});

document.querySelectorAll('.chart-shell').forEach((element) => {
    const labels = JSON.parse(element.dataset.chartLabels || '[]');
    const values = JSON.parse(element.dataset.chartValues || '[]');
    const colors = JSON.parse(element.dataset.chartColors || '[]');

    const app = createApp({
        render() {
            return h(PieChart, { labels, values, colors });
        },
    });

    app.mount(element);
});
