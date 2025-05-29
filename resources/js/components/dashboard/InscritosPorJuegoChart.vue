<script setup lang="ts">
import { PolarArea } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, RadialLinearScale, ArcElement } from 'chart.js';
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

ChartJS.register(Title, Tooltip, Legend, RadialLinearScale, ArcElement);

const page = usePage<{ inscritos_por_juego?: Array<{ nombre: string; total: number }> }>();
const inscritosPorJuego = computed(() => page.props.inscritos_por_juego ?? []);

const chartData = computed(() => ({
    labels: inscritosPorJuego.value.map((j) => j.nombre),
    datasets: [
        {
            label: 'Inscritos',
            backgroundColor: ['#72211d', '#e2d9ca', '#3c3c36', '#bfa181', '#a3a3a3', '#d9b99b', '#c41e3a', '#7c3c21', '#e2d9ca', '#3c3c36'],
            data: inscritosPorJuego.value.map((j) => j.total),
        },
    ],
}));

const chartOptions = {
    responsive: true,
    plugins: {
        legend: { position: 'right' as const },
        title: { display: true, text: 'Inscritos por Juego' },
    },
};
</script>

<template>
    <div class="flex flex-col md:flex-row items-center justify-center gap-6">
        <div style="max-width: 520px; min-width: 320px; width: 100%">
            <PolarArea :data="chartData" :options="chartOptions" />
        </div>
    </div>
</template>
