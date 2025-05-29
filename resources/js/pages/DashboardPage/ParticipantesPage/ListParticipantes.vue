<script setup lang="ts">
import Modal from '@/components/Modal.vue';
import { ref, onMounted, watch, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';

defineProps({
    role: {
        type: Boolean,
    },
});

const selectedGame = ref<number | null>(null);
const games = ref<{ id: number; nombre: string }[]>([]);
const inscripciones = ref<
    {
        id: number;
        user?: { id: number; name: string; phone: string };
        equipo?: { user?: { name: string; phone: string }; miembros?: any };
        nombre_juego: string;
        nro_comprobante: string;
        comprobante_pago: string;
        valor_comprobante: string;
        estado_pago: string;
    }[]
>([]);
const message = ref('');
const comprobanteSeleccionado = ref('');
const showModal = ref(false);

const hasTeamMembers = computed(() => {
    return inscripciones.value.some((inscripcion: { equipo?: { miembros?: any } }) => inscripcion.equipo && inscripcion.equipo.miembros);
});

const verComprobante = async (comprobante: string) => {
    comprobanteSeleccionado.value = comprobante;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

onMounted(async () => {
    const gameResponse = await axios.get('/api/juegos');
    games.value = gameResponse.data;

    if (games.value.length > 0) {
        selectedGame.value = games.value[0].id;
        await updateInscriptions();
    }
});

watch(selectedGame, async (newVal) => {
    if (newVal) {
        await updateInscriptions();
    }
});

const generateReport = async () => {
    const response = await axios.post('/report_participantes_by_game', { game: selectedGame.value }, { responseType: 'blob' });
    const url = window.URL.createObjectURL(new Blob([response.data]));
    const link = document.createElement('a');
    link.href = url;
    const contentDisposition = response.headers['content-disposition'];
    let fileName = 'report.pdf';
    if (contentDisposition) {
        const fileNameMatch = contentDisposition.match(/filename="(.+)"/);
        if (fileNameMatch.length === 2) fileName = fileNameMatch[1];
    }
    link.setAttribute('download', fileName);
    document.body.appendChild(link);
    link.click();
    link.remove();
};

const updateInscriptions = async () => {
    const inscriptionResponse = await axios.post('/get_inscripciones_by_game', { game: selectedGame.value });
    inscripciones.value = [...inscriptionResponse.data.inscripcionesIndividuales, ...inscriptionResponse.data.inscripcionesGrupales];
};

const updatePaymentStatus = async (participante: { id: number; user_id: number; id_juego: number; estado_pago: string }) => {
    await axios.post('/update_pago', {
        id: participante.id,
        user_id: participante.user_id,
        id_juego: participante.id_juego,
        estado_pago: participante.estado_pago,
    });
    message.value = 'El estado del pago ha sido actualizado.';
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Participantes',
        href: '/participantes',
    },
];
</script>

<template>
    <Head title="Participantes" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <template #header>
            <h2 class="font-semibold text-xl text-custom-black dark:text-custom-beige leading-tight">Participantes</h2>
        </template>

        <div class="py-12 animate__animated animate__fadeInUp">
            <div class="sm:px-6 lg:px-8">
                <div class="bg-custom-beige dark:bg-custom-gray shadow-lg sm:rounded-lg p-6 border border-custom-gray-light/20">
                    <!-- Selector de juego -->
                    <div class="mb-8">
                        <p class="my-2 text-custom-black dark:text-custom-beige font-medium">Selecciona un juego</p>
                        <div class="flex items-center space-x-4">
                            <select
                                v-model="selectedGame"
                                @change="updateInscriptions"
                                class="px-4 py-3 w-9/12 rounded-lg border-1 text-custom-black dark:text-custom-beige bg-white dark:bg-black border-custom-gray-light dark:border-custom-gray-light/30 focus:border-custom-wine focus:ring-2 focus:ring-custom-wine/20 transition-all duration-200"
                            >
                                <option v-for="game in games" :value="game.id" :key="game.id">
                                    {{ game.nombre }}
                                </option>
                            </select>
                            <button
                                type="button"
                                class="w-1/4 flex justify-center items-center px-4 py-3 bg-custom-wine hover:bg-custom-wine/80 text-custom-beige border rounded-lg border-wine font-medium transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
                                @click="generateReport"
                            >
                                Generar Reporte
                            </button>
                        </div>
                    </div>

                    <!-- Tabla de participantes -->
                    <div v-if="inscripciones.length" class="overflow-hidden rounded-lg shadow-lg border border-custom-gray-light/20">
                        <table class="w-full bg-white dark:bg-black">
                            <thead class="bg-custom-gray-light/10 dark:bg-custom-gray">
                                <th
                                    class="px-6 py-4 text-left text-sm font-semibold text-custom-black dark:text-custom-beige uppercase tracking-wider"
                                >
                                    #
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-sm font-semibold text-custom-black dark:text-custom-beige uppercase tracking-wider"
                                >
                                    Nombre
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-sm font-semibold text-custom-black dark:text-custom-beige uppercase tracking-wider"
                                >
                                    Teléfono
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-sm font-semibold text-custom-black dark:text-custom-beige uppercase tracking-wider"
                                >
                                    Juego
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-sm font-semibold text-custom-black dark:text-custom-beige uppercase tracking-wider"
                                >
                                    Nro. Comprobante
                                </th>
                                <th
                                    v-if="hasTeamMembers"
                                    class="px-6 py-4 text-left text-sm font-semibold text-custom-black dark:text-custom-beige uppercase tracking-wider"
                                >
                                    Miembros
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-sm font-semibold text-custom-black dark:text-custom-beige uppercase tracking-wider"
                                >
                                    Valor
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-sm font-semibold text-custom-black dark:text-custom-beige uppercase tracking-wider"
                                >
                                    Acción
                                </th>
                            </thead>
                            <tbody class="divide-y divide-custom-gray-light/20 dark:divide-custom-gray-light/10">
                                <tr
                                    v-for="(participante, i) in inscripciones"
                                    :key="participante.id"
                                    class="hover:bg-custom-beige/30 dark:hover:bg-custom-gray/50 transition-colors duration-150"
                                >
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-custom-gray dark:text-custom-gray-light">
                                        {{ i + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-custom-black dark:text-custom-beige">
                                        {{
                                            participante.user
                                                ? participante.user.name
                                                : participante.equipo && participante.equipo.user
                                                  ? participante.equipo.user.name
                                                  : ''
                                        }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-custom-gray dark:text-custom-gray-light">
                                        {{
                                            participante.user
                                                ? participante.user.phone
                                                : participante.equipo && participante.equipo.user
                                                  ? participante.equipo.user.phone
                                                  : ''
                                        }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-custom-gray dark:text-custom-gray-light">
                                        {{ participante.nombre_juego }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-custom-gray dark:text-custom-gray-light">
                                        <div class="flex items-center space-x-2">
                                            <span>{{ participante.nro_comprobante }}</span>
                                            <button
                                                @click="verComprobante(participante.comprobante_pago)"
                                                class="text-custom-wine hover:text-custom-wine/70 transition-colors duration-150 p-1 rounded"
                                            >
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td
                                        v-if="hasTeamMembers"
                                        class="px-6 py-4 whitespace-nowrap text-sm text-custom-gray dark:text-custom-gray-light"
                                    >
                                        {{ participante.equipo ? participante.equipo.miembros : '' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-custom-wine">
                                        ${{ parseFloat(participante.valor_comprobante).toFixed(2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <select
                                            v-model="participante.estado_pago"
                                            @change="
                                                updatePaymentStatus({
                                                    id: participante.id,
                                                    user_id: participante.user?.id || 0,
                                                    id_juego: selectedGame || 0,
                                                    estado_pago: participante.estado_pago,
                                                })
                                            "
                                            class="block w-full px-3 py-2 rounded-lg border-2 border-custom-gray-light/30 bg-White dark:bg-black text-custom-black dark:text-custom-beige focus:border-custom-wine focus:ring-2 focus:ring-custom-wine/20 transition-all duration-200"
                                        >
                                            <option value="verificado">Verificado</option>
                                            <option value="pendiente">Pendiente</option>
                                            <option value="cancelado">Cancelado</option>
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Mensaje de estado -->
                    <div v-if="message" class="mt-6 p-4 bg-white dark:bg-black border border-white dark:border-white rounded-lg">
                        <p class="text-wine dark:text-wine font-medium">{{ message }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para comprobante -->
        <Modal :show="showModal" @close="closeModal">
            <div v-if="showModal" class="flex items-center justify-center p-6 bg-white dark:bg-custom-black rounded-lg">
                <img
                    :src="'storage/' + comprobanteSeleccionado"
                    alt="Comprobante de Pago"
                    class="max-w-full max-h-96 rounded-lg shadow-lg border border-custom-gray-light/20"
                />
            </div>
        </Modal>
    </AppLayout>
</template>

<style scoped>
.custom-width {
    min-height: calc(80vh - 60px);
    height: calc(100vh - 60px);
}

/* Definición de colores personalizados */
:root {
    --color-custom-black: #070706;
    --color-custom-gray: #3c3c36;
    --color-custom-gray-light: #7c7c72;
    --color-custom-beige: #e2d9ca;
    --color-custom-wine: #72211d;
}

/* Clases de utilidad personalizadas */
.text-custom-black {
    color: var(--color-custom-black);
}
.text-custom-gray {
    color: var(--color-custom-gray);
}
.text-custom-gray-light {
    color: var(--color-custom-gray-light);
}
.text-custom-beige {
    color: var(--color-custom-beige);
}
.text-custom-wine {
    color: var(--color-custom-wine);
}

.bg-custom-black {
    background-color: var(--color-custom-black);
}
.bg-custom-gray {
    background-color: var(--color-custom-gray);
}
.bg-custom-gray-light {
    background-color: var(--color-custom-gray-light);
}
.bg-custom-beige {
    background-color: var(--color-custom-beige);
}
.bg-custom-wine {
    background-color: var(--color-custom-wine);
}

.border-custom-black {
    border-color: var(--color-custom-black);
}
.border-custom-gray {
    border-color: var(--color-custom-gray);
}
.border-custom-gray-light {
    border-color: var(--color-custom-gray-light);
}
.border-custom-beige {
    border-color: var(--color-custom-beige);
}
.border-custom-wine {
    border-color: var(--color-custom-wine);
}

/* Estados de hover y focus */
.hover\:bg-custom-wine\/80:hover {
    background-color: rgb(114 33 29 / 0.8);
}
.hover\:text-custom-wine\/70:hover {
    color: rgb(114 33 29 / 0.7);
}
.focus\:border-custom-wine:focus {
    border-color: var(--color-custom-wine);
}
.focus\:ring-custom-wine\/20:focus {
    --tw-ring-color: rgb(114 33 29 / 0.2);
    box-shadow: var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) var(--tw-ring-color);
}
</style>
