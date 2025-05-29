<script setup lang="ts">
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, CategoryScale, LinearScale, BarElement } from 'chart.js';
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

ChartJS.register(Title, Tooltip, Legend, CategoryScale, LinearScale, BarElement);

const page = usePage<{ inscritos_por_juego?: Array<{ nombre: string; total: number; modalidad?: string }> }>();
const inscritosPorJuego = computed(() => page.props.inscritos_por_juego ?? []);

const individuales = computed(() => inscritosPorJuego.value.filter((j) => j.modalidad === 'individual'));
const grupales = computed(() => inscritosPorJuego.value.filter((j) => j.modalidad === 'grupo'));

const chartDataIndividual = computed(() => ({
    labels: individuales.value.map((j) => j.nombre),
    datasets: [
        {
            label: 'Inscritos Individual',
            backgroundColor: ['#72211d', '#e2d9ca', '#3c3c36', '#bfa181', '#a3a3a3', '#d9b99b', '#c41e3a', '#7c3c21', '#e2d9ca', '#3c3c36'],
            data: individuales.value.map((j) => j.total),
        },
    ],
}));

const chartDataGrupal = computed(() => ({
    labels: grupales.value.map((j) => j.nombre),
    datasets: [
        {
            label: 'Inscritos Grupal',
            backgroundColor: ['#3c3c36', '#bfa181', '#a3a3a3', '#d9b99b', '#c41e3a', '#7c3c21', '#e2d9ca', '#72211d', '#e2d9ca', '#3c3c36'],
            data: grupales.value.map((j) => j.total),
        },
    ],
}));

const chartOptions = {
    responsive: true,
    plugins: {
        legend: { position: 'top' as const },
        title: { display: false },
    },
};
</script>

<template>
    <div class="flex flex-col md:flex-row items-center justify-center gap-6">
        <div style="max-width: 800px; min-width: 400px; width: 100%">
            <h3 class="text-lg font-semibold mb-2 text-center">Inscritos por Juego (Individual)</h3>
            <Bar
                :data="chartDataIndividual"
                :options="{
                    ...chartOptions,
                    plugins: { ...chartOptions.plugins, title: { display: true, text: 'Inscritos por Juego (Individual)' } },
                }"
            />
        </div>
        <div style="max-width: 800px; min-width: 400px; width: 100%">
            <h3 class="text-lg font-semibold mb-2 text-center">Inscritos por Juego (Grupal)</h3>
            <Bar
                :data="chartDataGrupal"
                :options="{ ...chartOptions, plugins: { ...chartOptions.plugins, title: { display: true, text: 'Inscritos por Juego (Grupal)' } } }"
            />
        </div>
    </div>
</template>
