<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Select from '@/components/ui/select/Select.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import Table from '@/components/ui/table/Table.vue';
import TableHeader from '@/components/ui/table/TableHeader.vue';
import TableRow from '@/components/ui/table/TableRow.vue';
import TableCell from '@/components/ui/table/TableCell.vue';
import UiLoader from '@/components/ui/UiLoader.vue';
import TableHead from '@/components/ui/table/TableHead.vue';
import Button from '@/components/ui/button/Button.vue';
import Modal from '@/components/Modal.vue';
import { EyeIcon } from 'lucide-vue-next';
import { toast } from 'vue-sonner';
import { Toaster } from '@/components/ui/sonner';
import { useForm } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Inscripciones',
        href: '/inscripciones',
    },
];

const tipo = ref<'individual' | 'grupo'>('individual');
const page = usePage();

const loading = ref(false); // Para mantener la interfaz compatible

const inscripcionesIndividuales = computed(() => {
    const data = page.props.inscripciones_individuales;
    return Array.isArray(data) ? data : [];
});
const inscripcionesGrupales = computed(() => {
    const data = page.props.inscripciones_grupales;
    return Array.isArray(data) ? data : [];
});

interface InscripcionRow {
    id: number | string;
    nombre: string;
    juego: string;
    modalidad: string;
    equipo: string;
    fecha: string;
    estado_pago: string;
    comprobante_pago?: string;
    _original?: any;
}

const inscripciones = computed((): InscripcionRow[] => {
    if (tipo.value === 'individual') {
        return inscripcionesIndividuales.value.map((item: any) => ({
            id: item.id,
            nombre: item.user?.name || item.nombre || 'Sin usuario',
            juego: item.juego?.nombre || item.juego_nombre || 'Sin juego',
            modalidad: 'Individual',
            equipo: '-',
            fecha: item.created_at ? item.created_at.substring(0, 16).replace('T', ' ') : item.fecha || '-',
            estado_pago: item.estado_pago || item.estado || 'pendiente',
            comprobante_pago: item.comprobante_pago || '',
            _original: item,
        }));
    } else {
        return inscripcionesGrupales.value.map((item: any) => ({
            id: item.id,
            nombre: item.equipo?.user?.name || item.equipo?.nombre_equipo || item.equipo_nombre || 'Sin equipo',
            juego: item.juego?.nombre || item.juego_nombre || 'Sin juego',
            modalidad: 'Grupal',
            equipo: item.equipo?.nombre_equipo || item.equipo_nombre || 'Sin equipo',
            fecha: item.created_at ? item.created_at.substring(0, 16).replace('T', ' ') : item.fecha || '-',
            estado_pago: item.estado_pago || item.estado || 'pendiente',
            comprobante_pago: item.comprobante_pago || '',
            _original: item,
        }));
    }
});

const showModal = ref(false);
const comprobanteSeleccionado = ref('');
const comprobanteImgLoading = ref(false);
const loadingPago = ref<string | number | null>(null);

const form = useForm({
    id: 0,
    user_id: 0,
    id_juego: 0,
    estado_pago: '',
    modalidad: '',
});

function verComprobante(comprobante: string) {
    comprobanteSeleccionado.value = comprobante;
    comprobanteImgLoading.value = true;
    showModal.value = true;
}
function onComprobanteImgLoad() {
    comprobanteImgLoading.value = false;
}
function closeModal() {
    showModal.value = false;
}

function updatePaymentStatus(item: InscripcionRow, newEstado: string) {
    loadingPago.value = item.id;
    let user_id = 0;
    let id_juego = 0;
    if (item.modalidad === 'Individual') {
        user_id = item._original?.user?.id || item._original?.user_id || 0;
        id_juego = item._original?.juego?.id || item._original?.id_juego || 0;
    } else {
        user_id = item._original?.equipo?.user?.id || 0;
        id_juego = item._original?.juego?.id || item._original?.id_juego || 0;
    }

    // Configurar el formulario con los datos necesarios
    form.id = item.id as number;
    form.user_id = user_id;
    form.id_juego = id_juego;
    form.estado_pago = newEstado;
    form.modalidad = item.modalidad;
    form.post('/inscripciones/actualizar-estado', {
        preserveScroll: true,
        onSuccess: () => {
            toast('Estado actualizado', {
                description: 'El estado del pago ha sido actualizado.',
            });
        },
        onError: (errors: any) => {
            toast('Error al actualizar', {
                description: errors?.message || 'No se pudo actualizar el estado.',
            });
        },
        onFinish: () => {
            loadingPago.value = null;
        },
    });
}
</script>

<template>
    <Head title="Inscripciones" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="w-full mt-8 p-6 bg-white dark:bg-black rounded-lg shadow-lg border border-gray-light/20">
            <div class="mb-6 flex items-center gap-4">
                <label class="font-semibold text-lg text-wine">Tipo de inscripción:</label>
                <Select v-model="tipo">
                    <SelectTrigger class="w-40 border-wine/40">
                        <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem value="individual">Individual</SelectItem>
                        <SelectItem value="grupo">Grupo</SelectItem>
                    </SelectContent>
                </Select>
            </div>
            <div v-if="loading" class="flex justify-center items-center py-12">
                <UiLoader size="lg" color="text-wine" />
            </div>
            <div v-else>
                <Table v-if="inscripciones.length" class="w-full">
                    <TableHeader>
                        <TableRow>
                            <TableHead>#</TableHead>
                            <TableHead>Nombre</TableHead>
                            <TableHead>Juego</TableHead>
                            <TableHead>Modalidad</TableHead>
                            <TableHead>Equipo</TableHead>
                            <TableHead>Fecha</TableHead>
                            <TableHead>Comprobante de Pago</TableHead>
                            <TableHead>Estado</TableHead>
                        </TableRow>
                    </TableHeader>
                    <tbody>
                        <TableRow v-for="(item, i) in inscripciones" :key="item.id">
                            <TableCell class="px-4 py-2">{{ i + 1 }}</TableCell>
                            <TableCell class="px-4 py-2">{{ item.nombre }}</TableCell>
                            <TableCell class="px-4 py-2">{{ item.juego }}</TableCell>
                            <TableCell class="px-4 py-2">{{ item.modalidad }}</TableCell>
                            <TableCell class="px-4 py-2">{{ item.equipo }}</TableCell>
                            <TableCell class="px-4 py-2">{{ item.fecha }}</TableCell>
                            <TableCell class="px-4 py-2">
                                <Button
                                    v-if="item.comprobante_pago && item.comprobante_pago !== ''"
                                    size="icon"
                                    class="bg-beige dark:bg-black text-white hover:text-wine/70 transition-colors duration-150 p-1 rounded cursor-pointer"
                                    @click="verComprobante(item.comprobante_pago)"
                                >
                                    <EyeIcon class="w-4 h-4" />
                                </Button>
                            </TableCell>
                            <TableCell class="px-4 py-2">
                                <Select
                                    v-model="item.estado_pago"
                                    @update:modelValue="
                                        (val) => {
                                            if (typeof val === 'string') updatePaymentStatus(item, val);
                                        }
                                    "
                                    :disabled="loadingPago === item.id"
                                    class="min-w-[120px]"
                                >
                                    <SelectTrigger class="w-full px-2 rounded border border-gray-200">
                                        <SelectValue />
                                        <span v-if="loadingPago === item.id" class="ml-2"><UiLoader size="sm" /></span>
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="verificado">Verificado</SelectItem>
                                        <SelectItem value="pendiente">Pendiente</SelectItem>
                                        <SelectItem value="cancelado">Cancelado</SelectItem>
                                    </SelectContent>
                                </Select>
                            </TableCell>
                        </TableRow>
                    </tbody>
                </Table>
                <div v-else class="text-center text-gray-500 py-8">No hay inscripciones para mostrar.</div>
            </div>
        </div>
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
        <Toaster></Toaster>
    </AppLayout>
</template>
