<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/components/Modal.vue';
import { ref, onMounted, watch, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';

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
</script>

<template>
    <Head title="Participantes" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Participantes</h2>
        </template>

        <div class="py-12 animate__animated animate__fadeInUp">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="custom-width bg-white overflow-scroll shadow-sm sm:rounded-lg p-6">
                    <div>
                        <p class="my-2">Selecciona un juego</p>
                        <div class="flex items-center space-x-4">
                            <select v-model="selectedGame" @change="updateInscriptions" class="px-4 py-2 w-9/12 rounded border border-gray-200">
                                <option v-for="game in games" :value="game.id" :key="game.id">{{ game.nombre }}</option>
                            </select>
                            <button
                                type="button"
                                class="w-1/4 flex justify-center items-center px-4 py-2 bg-purple-800 hover:bg-purple-700 text-white rounded"
                                @click="generateReport"
                            >
                                Generar Reporte
                            </button>
                        </div>
                    </div>

                    <table v-if="inscripciones.length" class="w-full bg-white rounded shadow overflow-hidden">
                        <thead class="bg-gray-50">
                            <th class="px-4 py-2">#</th>
                            <th class="px-4 py-2">Nombre</th>
                            <th class="px-4 py-2">Telefono</th>
                            <th class="px-4 py-2">Juego</th>
                            <th class="px-4 py-2">Nro. Comprobante</th>
                            <th class="px-4 py-2" v-if="hasTeamMembers">Miembros</th>
                            <th class="px-4 py-2">Valor</th>
                            <th class="px-4 py-2">Accion</th>
                        </thead>
                        <tbody>
                            <tr v-for="(participante, i) in inscripciones" :key="participante.id" class="text-gray-700">
                                <td class="px-4 py-2">{{ i + 1 }}</td>
                                <td class="px-4 py-2">
                                    {{
                                        participante.user
                                            ? participante.user.name
                                            : participante.equipo && participante.equipo.user
                                              ? participante.equipo.user.name
                                              : ''
                                    }}
                                </td>
                                <td class="px-4 py-2">
                                    {{
                                        participante.user
                                            ? participante.user.phone
                                            : participante.equipo && participante.equipo.user
                                              ? participante.equipo.user.phone
                                              : ''
                                    }}
                                </td>
                                <td class="px-4 py-2">{{ participante.nombre_juego }}</td>
                                <td class="px-4 py-2">
                                    {{ participante.nro_comprobante }}
                                    <button @click="verComprobante(participante.comprobante_pago)" class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                                <td class="px-4 py-2" v-if="hasTeamMembers">
                                    <!-- Nueva celda para los miembros del equipo -->
                                    {{ participante.equipo ? participante.equipo.miembros : '' }}
                                </td>
                                <td class="px-4 py-2">${{ parseFloat(participante.valor_comprobante).toFixed(2) }}</td>
                                <td class="px-4 py-2">
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
                                        class="block w-full px-2 py-1 rounded border border-gray-200"
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
                <div class="text-green-500 mt-2">{{ message }}</div>
            </div>
        </div>
        <Modal :show="showModal" @close="closeModal">
            <div v-if="showModal" class="flex items-center justify-center p-4">
                <img :src="'storage/' + comprobanteSeleccionado" alt="Comprobante de Pago" />
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

<style scoped>
.custom-width {
    min-height: calc(80vh - 60px);
    height: calc(100vh - 60px);
}
</style>
