<script setup>

import Chart from 'chart.js/auto';
import { computed, onMounted, ref } from "vue";
import ChartDataLabels from 'chartjs-plugin-datalabels';

const props = defineProps({
    id: String,
    label: String,
    title: String,
    data: Array
});

const graph = ref(null);
let x = "";

onMounted(() => {

    x = computed(() => {

        if (graph.value !== null) {
            graph.value.destroy();
        }

        graph.value = new Chart(
            document.getElementById(props.id),
            {
                type: 'pie',
                data: {
                    labels: props.data.map(row => row.label),
                    datasets: [
                        {
                            label: props.label,
                            data: props.data.map(row => row.value),
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.8)',
                                'rgba(255, 159, 64, 0.8)',
                                'rgba(255, 205, 86, 0.8)',
                                'rgba(75,192,98,0.8)',
                                'rgba(54, 162, 235, 0.8)',
                                'rgba(153, 102, 255, 0.8)',
                                'rgba(201, 203, 207, 0.8)',
                                'rgba(50, 50, 50, 0.8)',
                                'rgba(15,248,229,0.8)',
                                'rgba(46,238,82,0.8)'
                            ]
                        }
                    ]
                },
                plugins: [
                    {
                        id: "bgColor",
                        beforeDraw: (chart, options) => {
                            const {ctx, width, height} = chart;
                            ctx.fillStyle = "white";
                            ctx.fillRect(0, 0, width, height);
                            ctx.restore();
                        }
                    },
                    ChartDataLabels
                ]
            }
        );

        return "";
    });
})
</script>
<template>
    <div class="flex flex-col items-center justify-center w-3/4 mx-auto my-2">
        <h2 v-if="data.length > 0" class="w-full text-center">{{ title }}</h2>
        <canvas :id="id"></canvas>
        <span class="hidden">{{ x }}</span>
    </div>
</template>
