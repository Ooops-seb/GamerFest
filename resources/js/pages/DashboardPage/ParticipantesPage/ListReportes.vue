<script setup lang="ts">
import Modal from '@/components/Modal.vue';
import { ref, onMounted, watch, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import TableHeader from '@/components/ui/table/TableHeader.vue';
import TableRow from '@/components/ui/table/TableRow.vue';
import TableCell from '@/components/ui/table/TableCell.vue';
import Table from '@/components/ui/table/Table.vue';
import Select from '@/components/ui/select/Select.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import Button from '@/components/ui/button/Button.vue';
import { EyeIcon, XIcon, PointerIcon } from 'lucide-vue-next';
import UiLoader from '@/components/ui/UiLoader.vue';
import { Toaster } from '@/components/ui/sonner';
import { toast } from 'vue-sonner';

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
const comprobanteImgLoading = ref(false);

const loadingGames = ref(false);
const loadingInscriptions = ref(false);
const loadingReport = ref(false);
const loadingPago = ref<number | null>(null);

const hasTeamMembers = computed(() => {
    return inscripciones.value.some((inscripcion: { equipo?: { miembros?: any } }) => inscripcion.equipo && inscripcion.equipo.miembros);
});

const verComprobante = async (comprobante: string) => {
    comprobanteSeleccionado.value = comprobante;
    comprobanteImgLoading.value = true;
    showModal.value = true;
};

const onComprobanteImgLoad = () => {
    comprobanteImgLoading.value = false;
};

const closeModal = () => {
    showModal.value = false;
};

onMounted(async () => {
    loadingGames.value = true;
    try {
        const gameResponse = await axios.get('/api/juegos');
        games.value = gameResponse.data;
        // No selecciona automáticamente el primer juego
        // selectedGame.value = games.value.length > 0 ? games.value[0].id : null;
    } finally {
        loadingGames.value = false;
    }
});

watch(selectedGame, async (newVal) => {
    if (newVal) {
        await updateInscriptions();
    } else {
        inscripciones.value = [];
    }
});

const generateReport = async () => {
    loadingReport.value = true;
    try {
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
    } finally {
        loadingReport.value = false;
    }
};

const updateInscriptions = async () => {
    loadingInscriptions.value = true;
    try {
        const inscriptionResponse = await axios.post('/get_inscripciones_by_game', { game: selectedGame.value });
        inscripciones.value = [...inscriptionResponse.data.inscripcionesIndividuales, ...inscriptionResponse.data.inscripcionesGrupales];
    } finally {
        loadingInscriptions.value = false;
    }
};

const updatePaymentStatus = async (participante: { id: number; user_id: number; id_juego: number; estado_pago: string }) => {
    loadingPago.value = participante.id;
    try {
        await axios.post('/update_pago', {
            id: participante.id,
            user_id: participante.user_id,
            id_juego: participante.id_juego,
            estado_pago: participante.estado_pago,
        });
        toast('Estado actualizado', {
            description: 'El estado del pago ha sido actualizado.',
        });
        message.value = '';
    } finally {
        loadingPago.value = null;
    }
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Reportes',
        href: '/reportes',
    },
];
</script>

<template>
    <Head title="Reportes" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-12 animate__animated animate__fadeInUp">
            <div class="sm:px-6 lg:px-8">
                <div class="bg-beige dark:bg-black shadow-lg sm:rounded-lg p-6 border border-gray-light/20">
                    <!-- Selector de juego -->
                    <div class="mb-8">
                        <p class="my-2 text-black dark:text-beige font-medium">Selecciona un juego</p>
                        <div class="flex items-center space-x-4">
                            <Select v-model="selectedGame" :disabled="loadingGames">
                                <SelectTrigger
                                    class="px-4 py-3 w-9/12 rounded-lg border-1 text-black dark:text-beige dark:bg-black border-gray-light dark:border-gray-light/30 focus:border-wine focus:ring-2 focus:ring-wine/20 transition-all duration-200 flex items-center cursor-pointer"
                                >
                                    <SelectValue placeholder="Selecciona un juego" />
                                    <span v-if="loadingGames" class="ml-2"><UiLoader size="sm" /></span>
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem :value="null" disabled>Selecciona un juego</SelectItem>
                                    <template v-for="game in games" :key="game.id">
                                        <SelectItem :value="game.id" class="cursor-pointer">{{ game.nombre }}</SelectItem>
                                    </template>
                                </SelectContent>
                            </Select>
                            <Button
                                type="button"
                                class="w-1/4 flex justify-center items-center px-4 py-3 bg-wine hover:bg-wine/80 text-beige border rounded-lg border-wine font-medium transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 cursor-pointer"
                                @click="generateReport"
                                :disabled="loadingReport || !selectedGame"
                            >
                                <UiLoader v-if="loadingReport" class="mr-2" size="sm" />
                                Generar Reporte
                            </Button>
                        </div>
                    </div>

                    <!-- Loading inscripciones -->
                    <div v-if="loadingInscriptions" class="flex justify-center items-center py-12">
                        <UiLoader size="lg" color="text-wine" />
                    </div>
                    <Table
                        v-else-if="inscripciones.length"
                        class="overflow-hidden rounded-lg shadow-lg border border-gray-light/20 w-full dark:bg-black"
                    >
                        <TableHeader class="bg-gray-light/10 dark:bg-gray">
                            <TableRow>
                                <TableCell
                                    as="th"
                                    class="px-6 py-4 text-left text-sm font-semibold text-black dark:text-beige uppercase tracking-wider"
                                    >#</TableCell
                                >
                                <TableCell
                                    as="th"
                                    class="px-6 py-4 text-left text-sm font-semibold text-black dark:text-beige uppercase tracking-wider"
                                    >Nombre</TableCell
                                >
                                <TableCell
                                    as="th"
                                    class="px-6 py-4 text-left text-sm font-semibold text-black dark:text-beige uppercase tracking-wider"
                                    >Teléfono</TableCell
                                >
                                <TableCell
                                    as="th"
                                    class="px-6 py-4 text-left text-sm font-semibold text-black dark:text-beige uppercase tracking-wider"
                                    >Juego</TableCell
                                >
                                <TableCell
                                    as="th"
                                    class="px-6 py-4 text-left text-sm font-semibold text-black dark:text-beige uppercase tracking-wider"
                                    >Nro. Comprobante</TableCell
                                >
                                <TableCell
                                    v-if="hasTeamMembers"
                                    as="th"
                                    class="px-6 py-4 text-left text-sm font-semibold text-black dark:text-beige uppercase tracking-wider"
                                    >Miembros</TableCell
                                >
                                <TableCell
                                    as="th"
                                    class="px-6 py-4 text-left text-sm font-semibold text-black dark:text-beige uppercase tracking-wider"
                                    >Valor</TableCell
                                >
                                <TableCell
                                    as="th"
                                    class="px-6 py-4 text-left text-sm font-semibold text-black dark:text-beige uppercase tracking-wider"
                                    >Acción</TableCell
                                >
                            </TableRow>
                        </TableHeader>
                        <tbody class="divide-y divide-gray-light/20 dark:divide-gray-light/10">
                            <TableRow
                                v-for="(participante, i) in inscripciones"
                                :key="participante.id"
                                class="hover:bg-beige/30 dark:hover:bg-gray/50 transition-colors duration-150"
                            >
                                <TableCell class="px-6 py-4 whitespace-nowrap text-sm text-gray dark:text-gray-light">{{ i + 1 }}</TableCell>
                                <TableCell class="px-6 py-4 whitespace-nowrap text-sm font-medium text-black dark:text-beige">
                                    {{
                                        participante.user
                                            ? participante.user.name
                                            : participante.equipo && participante.equipo.user
                                              ? participante.equipo.user.name
                                              : ''
                                    }}
                                </TableCell>
                                <TableCell class="px-6 py-4 whitespace-nowrap text-sm text-gray dark:text-gray-light">
                                    {{
                                        participante.user
                                            ? participante.user.phone
                                            : participante.equipo && participante.equipo.user
                                              ? participante.equipo.user.phone
                                              : ''
                                    }}
                                </TableCell>
                                <TableCell class="px-6 py-4 whitespace-nowrap text-sm text-gray dark:text-gray-light">
                                    {{ participante.nombre_juego }}
                                </TableCell>
                                <TableCell class="px-6 py-4 whitespace-nowrap text-sm text-gray dark:text-gray-light">
                                    <div class="flex items-center space-x-2">
                                        <span>{{ participante.nro_comprobante }}</span>
                                        <Button
                                            @click="verComprobante(participante.comprobante_pago)"
                                            class="bg-beige dark:bg-black text-white hover:text-wine/70 transition-colors duration-150 p-1 rounded cursor-pointer"
                                        >
                                            <EyeIcon></EyeIcon>
                                        </Button>
                                    </div>
                                </TableCell>
                                <TableCell v-if="hasTeamMembers" class="px-6 py-4 whitespace-nowrap text-sm text-gray dark:text-gray-light">
                                    {{ participante.equipo ? participante.equipo.miembros : '' }}
                                </TableCell>
                                <TableCell class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-wine">
                                    ${{ parseFloat(participante.valor_comprobante).toFixed(2) }}
                                </TableCell>
                                <TableCell class="px-6 py-4 whitespace-nowrap text-sm">
                                    <Select
                                        v-model="participante.estado_pago"
                                        @update:modelValue="
                                            () =>
                                                updatePaymentStatus({
                                                    id: participante.id,
                                                    user_id: participante.user?.id || 0,
                                                    id_juego: selectedGame || 0,
                                                    estado_pago: participante.estado_pago,
                                                })
                                        "
                                        :disabled="loadingPago === participante.id"
                                    >
                                        <SelectTrigger
                                            class="w-full px-3 rounded-lg border-2 border-gray/30 dark:bg-black text-black dark:text-beige focus:border-wine focus:ring-2 focus:ring-wine/20 transition-all duration-200 flex items-center cursor-pointer"
                                        >
                                            <SelectValue />
                                            <span v-if="loadingPago === participante.id" class="ml-2"><UiLoader size="sm" /></span>
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="verificado" class="cursor-pointer">Verificado</SelectItem>
                                            <SelectItem value="pendiente" class="cursor-pointer">Pendiente</SelectItem>
                                            <SelectItem value="cancelado" class="cursor-pointer">Cancelado</SelectItem>
                                        </SelectContent>
                                    </Select>
                                </TableCell>
                            </TableRow>
                        </tbody>
                    </Table>
                    <!-- Mensaje si no hay inscripciones o no se ha seleccionado juego -->
                    <div v-else class="flex flex-col items-center justify-center py-12 text-gray dark:text-gray-light">
                        <PointerIcon v-if="selectedGame === null" class="w-8 h-8 mb-2" />
                        <XIcon v-else class="w-8 h-8 mb-2" />
                        <span class="text-lg font-medium">
                            {{
                                selectedGame === null
                                    ? 'Selecciona un juego para ver sus inscripciones.'
                                    : 'No se encuentran inscripciones en este juego.'
                            }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para comprobante -->
        <Modal :show="showModal" @close="closeModal">
            <div v-if="showModal" class="flex items-center justify-center p-6 dark:bg-black rounded-lg min-h-[300px] min-w-[300px]">
                <UiLoader v-if="comprobanteImgLoading" size="lg" color="text-wine" />
                <img
                    v-show="!comprobanteImgLoading"
                    :src="comprobanteSeleccionado"
                    alt="Comprobante de Pago"
                    class="max-w-full max-h-96 rounded-lg shadow-lg border border-gray-light/20"
                    @load="onComprobanteImgLoad"
                />
            </div>
        </Modal>
        <Toaster />
    </AppLayout>
</template>
