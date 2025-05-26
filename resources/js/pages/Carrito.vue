<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { reactive, onMounted, ref, watchEffect } from 'vue';
import Swal from 'sweetalert2';
import InputError from '@/../../resources/js/components/InputError.vue';
import TextInput from '@/../../resources/js/components/TextInput.vue';
import Navbar from '@/../../resources/js/components/welcome/navigation/Navbar.vue';
import Modal from '@/../../resources/js/components/Modal.vue';
import axios from 'axios';
import supabase from '@/lib/supabase';
import InputLabel from '@/components/InputLabel.vue';
import Button from '@/components/ui/button/Button.vue';
import { Check, Plus, Trash } from 'lucide-vue-next';
import Input from '@/components/ui/input/Input.vue';
import { Ads } from '@/types/api/Ad.type';
import icon from '@/../../public/images/Icon2025black.webp';

defineProps({
    ads: {
        type: Array as () => Ads[],
        default: () => [],
    },
});

const billingData = {
    name: import.meta.env.VITE_BILLING_NAME ?? 'Gamer Fest',
    account: import.meta.env.VITE_BILLING_ACCOUNT ?? '123456789',
    bank: import.meta.env.VITE_BILLING_BANK ?? 'Banco Gamer',
    ci: import.meta.env.VITE_BILLING_CI ?? '032456987',
    type: import.meta.env.VITE_BILLING_TYPE ?? 'Ahorros',
};

interface AuthUser {
    id: number;
}

interface Equipo {
    id: number;
    id_juego: number;
    nombre_equipo: string;
    miembros: string;
}

interface Juego {
    id: number;
    nombre: string;
    genero: string;
    modalidad: string;
    costo_inscripcion: number;
    img_id: string;
}

interface PageProps {
    auth: {
        user: AuthUser;
    };
    [key: string]: any; // Add index signature to satisfy the constraint
}

const props = usePage<PageProps>().props as PageProps;

const equipos = ref<Equipo[]>([]);
const juegosInscritos = ref<Juego[]>(JSON.parse(localStorage.getItem('juegosInscritos') ?? '[]'));
const numJuegosSeleccionados = ref(juegosInscritos.value.length);

watchEffect(() => {
    const stored = JSON.parse(localStorage.getItem('juegosInscritos') ?? '[]');
    juegosInscritos.value = stored;
    numJuegosSeleccionados.value = stored.length;
});

const state = reactive<{ juegos: Juego[]; total: number; loading: boolean }>({
    juegos: [],
    total: 0,
    loading: false,
});

const form = useForm({
    user_id: props.auth.user?.id ?? null,
    juegos: [] as number[],
    estado: 'inscrito',
    nro_comprobante: '',
    valor_comprobante: 0.0,
    comprobante_pago: null as File | null,
    loading: false as boolean,
});

const formEquipo = useForm({
    id_equipo: '',
    id_juego: '',
    user_id: '',
    nombre_equipo: '',
    miembro: '',
});

const showModal = ref(false);
const miembros = ref<string[]>([]);
const uploadedFileName = ref<string | null>(null);
const isFileUploaded = ref(false);

const numeroMaxJugadores = 5;

const addMember = () => {
    if (formEquipo.miembro && miembros.value.length < numeroMaxJugadores) {
        miembros.value.push(formEquipo.miembro);
        formEquipo.miembro = '';
    } else if (miembros.value.length >= numeroMaxJugadores) {
        Swal.fire({
            title: 'Máximo de miembros alcanzado',
            text: `El equipo ya tiene el máximo de ${numeroMaxJugadores} miembros.`,
            icon: 'warning',
            background: '#1a1a1a',
            color: '#f5f5f5',
            iconColor: '#c41e3a',
            customClass: {
                popup: 'rounded-xl shadow-lg border border-[#c41e3a]',
                title: 'text-xl font-bold font-cinzel',
                htmlContainer: 'text-sm font-light',
                confirmButton: 'text-sm px-6 py-2',
            },
        });
    }
};

const removeMember = (index: number) => miembros.value.splice(index, 1);

const closeModal = () => (showModal.value = false);

const createTeam = async () => {
    const formData = new FormData();
    formData.append('nombre_equipo', formEquipo.nombre_equipo);
    formData.append('user_id', props.auth.user.id.toString());
    formData.append('id_juego', formEquipo.id_juego);
    formData.append('miembros', JSON.stringify(miembros.value));

    try {
        await axios.post('/equipos', formData);
        Swal.fire({
            title: 'Equipo creado',
            text: 'El equipo ha sido creado correctamente!',
            icon: 'success',
            background: '#1a1a1a',
            color: '#f5f5f5',
            iconColor: '#00ffcc',
            customClass: {
                popup: 'rounded-xl shadow-lg border border-[#c41e3a]',
                title: 'text-xl font-bold font-cinzel',
                htmlContainer: 'text-sm font-light',
                confirmButton: 'text-sm px-6 py-2',
            },
        });
        formEquipo.reset();
        miembros.value = [];
        closeModal();
        recuperarEquipos();
    } catch (error) {
        console.error('Error al crear el equipo:', error);
        Swal.fire({
            title: 'Error',
            text: 'Hubo un error al crear el equipo',
            icon: 'error',
            background: '#1a1a1a',
            color: '#f5f5f5',
            iconColor: '#00ffcc',
            customClass: {
                popup: 'rounded-xl shadow-lg border border-[#c41e3a]',
                title: 'text-xl font-bold font-cinzel',
                htmlContainer: 'text-sm font-light',
                confirmButton: 'text-sm px-6 py-2',
            },
        });
    }
};

const updateTeam = async () => {
    const data = {
        id: formEquipo.id_equipo,
        id_juego: formEquipo.id_juego,
        nombre_equipo: formEquipo.nombre_equipo,
        user_id: props.auth.user.id,
        miembros: JSON.stringify(miembros.value),
    };

    try {
        await axios.put(`/equipos/${data.id}`, data);
        Swal.fire({
            title: 'Equipo actualizado',
            text: 'El equipo ha sido actualizado correctamente!',
            icon: 'success',
            background: '#1a1a1a',
            color: '#f5f5f5',
            iconColor: '#00ffcc',
            customClass: {
                popup: 'rounded-xl shadow-lg border border-[#c41e3a]',
                title: 'text-xl font-bold font-cinzel',
                htmlContainer: 'text-sm font-light',
                confirmButton: 'text-sm px-6 py-2',
            },
        });
        formEquipo.reset();
        miembros.value = [];
        closeModal();
        recuperarEquipos();
    } catch (error) {
        console.error('Error al actualizar el equipo:', error);
        Swal.fire('Error', 'Hubo un error al actualizar el equipo', 'error');
    }
};

const recuperarEquipos = async () => {
    const formData = new FormData();
    formData.append('user_id', props.auth.user.id.toString());

    try {
        const response = await axios.post('/equipo_por_juego', formData);
        equipos.value = response.data;
    } catch (error) {
        console.error(error);
    }
};

const openModal = (juegoId: number) => {
    const equipo = equipos.value.find((e) => e.id_juego === juegoId);

    formEquipo.id_juego = juegoId.toString();
    formEquipo.user_id = props.auth.user.id.toString();

    if (equipo) {
        formEquipo.id_equipo = equipo.id.toString();
        formEquipo.nombre_equipo = equipo.nombre_equipo;
        miembros.value = JSON.parse(equipo.miembros);
    } else {
        formEquipo.id_equipo = '';
        formEquipo.nombre_equipo = '';
        miembros.value = [];
    }

    showModal.value = true;
};

const getTotal = async () => {
    state.loading = true;
    const stored = JSON.parse(localStorage.getItem('juegosInscritos') ?? '[]');
    const response = await axios.post('/api/juegos/cargar-carrito', { juegosInscritos: stored });

    state.juegos = response.data.juegos;
    state.total = response.data.total;
    form.juegos = response.data.juegos;
    state.loading = false;
};

const updateFileName = () => {
    if (form.comprobante_pago) {
        const filename = form.comprobante_pago.name;
        uploadedFileName.value = filename.length > 12 ? filename.slice(0, 12) : filename;
        isFileUploaded.value = true;
    } else {
        uploadedFileName.value = null;
        isFileUploaded.value = false;
    }
};

const removeJuego = (index: number) => {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción eliminará el juego.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        background: '#1a1a1a',
        color: '#f5f5f5',
        iconColor: '#00ffcc',
        confirmButtonColor: '#c41e3a',
        customClass: {
            popup: 'rounded-xl shadow-lg border border-[#c41e3a]',
            title: 'text-xl font-bold font-cinzel',
            htmlContainer: 'text-sm font-light',
            confirmButton: 'text-sm px-6 py-2',
        },
    }).then((result) => {
        if (result.isConfirmed) {
            state.juegos.splice(index, 1);
            localStorage.setItem('juegosInscritos', JSON.stringify(state.juegos));
            // Actualizar juegosInscritos y numJuegosSeleccionados manualmente
            juegosInscritos.value = [...state.juegos];
            numJuegosSeleccionados.value = state.juegos.length;
            Swal.fire({
                title: 'Eliminado',
                text: 'El juego ha sido eliminado.',
                icon: 'success',
                background: '#1a1a1a',
                color: '#f5f5f5',
                iconColor: '#00ffcc',
                customClass: {
                    popup: 'rounded-xl shadow-lg border border-[#c41e3a]',
                    title: 'text-xl font-bold font-cinzel',
                    htmlContainer: 'text-sm font-light',
                },
            });
            getTotal();
        }
    });
};

const submitForm = async () => {
    form.loading = true;

    const juegos = form.juegos;
    const valor_comprobante = parseFloat(state.total.toString()).toFixed(2);
    let nro_comprobante: string | null = null;
    let comprobante_pago: File | null = null;

    if (valor_comprobante === '0.00') {
        nro_comprobante = 'no aplica';
    } else {
        nro_comprobante = form.nro_comprobante;
        const fileInput = document.getElementById('comprobante_pago') as HTMLInputElement | null;
        comprobante_pago = fileInput?.files?.[0] || null;

        if (!nro_comprobante) {
            Swal.fire({
                title: 'Error',
                text: 'Es necesario ingresar el número de comprobante',
                icon: 'error',
                background: '#1a1a1a',
                color: '#f5f5f5',
                iconColor: '#c41e3a',
                confirmButtonColor: '#c41e3a',
                customClass: {
                    popup: 'rounded-xl shadow-lg border border-[#c41e3a]',
                    title: 'text-xl font-bold font-cinzel',
                    htmlContainer: 'text-sm font-light',
                    confirmButton: 'text-sm px-6 py-2',
                },
            });
            form.loading = false;
            return;
        }

        if (!comprobante_pago) {
            Swal.fire({
                title: 'Error',
                text: 'Es necesario subir el comprobante de pago',
                icon: 'error',
                background: '#1a1a1a',
                color: '#f5f5f5',
                iconColor: '#c41e3a',
                confirmButtonColor: '#c41e3a',
                customClass: {
                    popup: 'rounded-xl shadow-lg border border-[#c41e3a]',
                    title: 'text-xl font-bold font-cinzel',
                    htmlContainer: 'text-sm font-light',
                    confirmButton: 'text-sm px-6 py-2',
                },
            });
            form.loading = false;
            return;
        }
    }

    const formData = new FormData();
    formData.append('user_id', form.user_id?.toString() ?? '');
    formData.append('juegos', JSON.stringify(juegos));
    formData.append('estado', form.estado);
    formData.append('nro_comprobante', nro_comprobante ?? '');
    formData.append('valor_comprobante', valor_comprobante);
    const imgName = `${form.user_id}/${nro_comprobante}_comprobante`;

    if (comprobante_pago) {
        const { data, error } = await supabase.storage.from('inscripciones').upload(imgName, comprobante_pago, {
            cacheControl: '3600',
            upsert: false,
        });
        if (error) {
            return Swal.fire({
                title: 'Error',
                text: 'Error al subir el comprobante de pago',
                icon: 'error',
                background: '#1a1a1a',
                color: '#f5f5f5',
                iconColor: '#c41e3a',
                customClass: {
                    popup: 'rounded-xl shadow-lg border border-[#c41e3a]',
                    title: 'text-xl font-bold font-cinzel',
                    htmlContainer: 'text-sm font-light',
                    confirmButton: 'text-sm px-6 py-2',
                },
            });
        }

        const { data: publicData } = await supabase.storage.from('inscripciones').getPublicUrl(data.path);
        formData.append('comprobante_pago', publicData?.publicUrl || '');
    }

    if (juegos.length > 3) {
        const confirm = await Swal.fire({
            title: 'Confirmación',
            text: 'Tienes más de 3 juegos inscritos. Algunos juegos pueden ser simultáneos. ¿Continuar?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, continuar',
            cancelButtonText: 'Modificar Carrito',
        });

        if (!confirm.isConfirmed) return;
    }
    // Removed unused variable isDarkMode
    try {
        await axios.post('/guardar_all_inscripciones', formData);
        Swal.fire({
            title: '¡Inscripción creada!',
            text: 'Se ha registrado correctamente.',
            icon: 'success',
            confirmButtonText: 'Aceptar',
            background: '#1a1a1a',
            color: '#f5f5f5',
            iconColor: '#00ffcc',
            confirmButtonColor: '#c41e3a',
            customClass: {
                popup: 'rounded-xl shadow-lg border border-[#c41e3a]',
                title: 'text-xl font-bold font-cinzel',
                htmlContainer: 'text-sm font-light',
                confirmButton: 'text-sm px-6 py-2',
            },
        }).then(() => {
            localStorage.removeItem('juegosInscritos');
            window.location.href = '/';
        });
    } catch (error) {
        let msg = 'Error inesperado al crear la inscripción';
        await supabase.storage.from('inscripciones').remove([imgName]);
        if (axios.isAxiosError(error) && error.response) msg = error.response.data.message;
        Swal.fire({
            title: 'Error',
            text: msg,
            icon: 'error',
            background: '#1a1a1a',
            color: '#f5f5f5',
            iconColor: '#c41e3a',
            customClass: {
                popup: 'rounded-xl shadow-lg border border-[#c41e3a]',
                title: 'text-xl font-bold font-cinzel',
                htmlContainer: 'text-sm font-light',
                confirmButton: 'text-sm px-6 py-2',
            },
        });
    } finally {
        form.loading = false;
    }
};

onMounted(() => {
    getTotal();
    recuperarEquipos();
});
</script>

<template>
    <Head title="Carrito" />
    <div class="fixed top-0 left-0 w-full navbar-container animate__animated animate__fadeInDown">
        <Navbar
            :ads="ads"
            :can-login="false"
            :can-register="false"
            :num-juegos-seleccionados="numJuegosSeleccionados"
            :juegos-inscritos="juegosInscritos"
        >
        </Navbar>
        <ul v-for="juego in state.juegos" :key="juego.id"></ul>
    </div>
    <div class="mt-20">
        <div class="max-w-xl mx-auto">
            <div
                v-if="!state.loading || juegosInscritos.length == 0"
                class="ac-container transition-all duration-500 ease-in-out transform hover:scale-[1.01]"
                style="margin-top: 140px"
            >
                <div class="ac-header">
                    <h2 class="ac-title">Carrito de Compras</h2>
                </div>
                <div v-if="state.juegos.length > 0">
                    <ul v-for="(juego, index) in state.juegos" :key="juego.id" role="list" class="divide-y divide-ac-gray">
                        <li class="pt-3 pb-0 sm:pt-2 transition-all duration-300 ease-in-out hover:bg-ac-gray/20">
                            <div class="flex flex-col sm:flex-row items-center space-x-2 sm:space-x-4 justify-between">
                                <!-- Contenido a la izquierda -->
                                <div class="flex justify-between items-center space-x-4 sm:space-x-6 sm:space-y-4 mb-4 sm:mb-0 part-1">
                                    <div class="flex-shrink-0 relative">
                                        <div class="ac-image-frame"></div>
                                        <img class="w-20 h-18 rounded-none ac-image" :src="`${juego.img_id ?? icon}`" alt="Game image" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-ac-beige truncate ac-game-title">
                                            {{ juego.nombre }}
                                        </p>
                                        <p class="text-sm text-ac-gray-light truncate">{{ juego.genero }}/{{ juego.modalidad }}</p>
                                    </div>
                                </div>

                                <!-- Contenido a la derecha -->
                                <div class="flex flex-row items-center space-x-4 sm:space-x-4 part-2">
                                    <div v-if="juego.modalidad == 'grupo'">
                                        <Button
                                            v-if="equipos.length && equipos.find((equipo) => equipo.id_juego === juego.id)"
                                            @click="openModal(juego.id)"
                                            class="ac-button ac-button-edit"
                                        >
                                            <span class="ac-button-text">Editar equipo</span>
                                        </Button>
                                        <Button v-else @click="openModal(juego.id)" class="ac-button ac-button-create">
                                            <span class="ac-button-text text-black dark:text-white">Crear equipo</span>
                                        </Button>
                                    </div>

                                    <div class="inline-flex items-center text-sm font-bold text-ac-beige">
                                        <span class="ac-price dark:text-white">$ {{ juego.costo_inscripcion }}</span>
                                    </div>
                                    <div class="inline-flex items-center text-base font-semibold">
                                        <Button @click="removeJuego(index)" class="ac-delete-button group">
                                            <div class="ac-delete-wrapper">
                                                <Trash class="w-4 text-wine"></Trash>
                                            </div>
                                        </Button>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>

                    <Modal :show="showModal" @close="closeModal">
                        <div class="p-6 ac-modal">
                            <h3 class="ac-modal-title">Nuevo equipo</h3>
                            <div>
                                <div>
                                    <InputLabel for="nombre_equipo" value="Nombre del equipo" />
                                    <TextInput
                                        id="nombre_equipo"
                                        type="text"
                                        class="mt-1 block w-full ac-input"
                                        v-model="formEquipo.nombre_equipo"
                                        required
                                    />
                                    <InputError class="mt-2" :message="formEquipo.errors.nombre_equipo" />
                                </div>

                                <form class="mt-4" @submit.prevent="addMember">
                                    <InputLabel for="miembro" value="Miembro" class="text-ac-beige" />
                                    <TextInput id="miembro" type="text" class="mt-1 block w-full ac-input" v-model="formEquipo.miembro" />
                                    <span class="text-sm text-ac-gray-light"
                                        >(Ingresa el nombre real de cada integrante uno a la vez, da click a añadir miembro. Ej. John Doe)</span
                                    >
                                    <InputError class="mt-2" :message="formEquipo.errors.miembro" />
                                    <Button
                                        type="submit"
                                        :disabled="!formEquipo.miembro"
                                        class="flex justify-center items-center space-x-1 mt-2 disabled:text-gray disabled:cursor-not-allowed px-2 py-1 rounded transition-colors duration-300 cursor-pointer"
                                    >
                                        <Plus></Plus>
                                        <span>Añadir miembro</span>
                                    </Button>
                                </form>

                                <div v-if="miembros.length" class="mt-4">
                                    <InputLabel value="Miembros del equipo" />
                                    <ul class="ac-member-list">
                                        <li class="ac-member-item" v-for="(miembro, index) in miembros" :key="index">
                                            {{ miembro }}
                                            <button class="ac-remove-member" @click.prevent="removeMember(index)">&times;</button>
                                        </li>
                                    </ul>
                                </div>

                                <div class="flex items-center justify-end mt-4">
                                    <template v-if="formEquipo.id_equipo !== '' && formEquipo.id_equipo !== null">
                                        <Button
                                            class="ml-4 ac-primary-button"
                                            :class="{ 'opacity-25': form.processing }"
                                            :disabled="form.processing"
                                            @click.prevent="updateTeam"
                                        >
                                            <span>Actualizar equipo</span>
                                        </Button>
                                    </template>
                                    <template v-else>
                                        <Button
                                            class="ml-4 ac-primary-button cursor-pointer"
                                            :class="{ 'opacity-25': form.processing }"
                                            :disabled="form.processing"
                                            @click.prevent="createTeam"
                                        >
                                            <span>Crear equipo</span>
                                        </Button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </Modal>

                    <div class="ac-total-container">
                        <strong class="text-ac-beige text-black dark:text-white">Total: </strong>
                        <span class="text-ac-beige text-base"
                            >$<span class="ac-total-amount text-black dark:text-white">{{ state.total.toFixed(2) }}</span></span
                        >
                    </div>

                    <form class="flex flex-col py-4 gap-y-2" @submit.prevent="submitForm">
                        <Input
                            v-if="state.total !== 0"
                            placeholder="Número de comprobante"
                            class="ac-cart-input"
                            type="text"
                            name="nro_comprobante"
                            id="nro_comprobante"
                            v-model="form.nro_comprobante"
                        />
                        <span class="text-black dark:text-beige">
                            Archivo: <small>{{ form.comprobante_pago?.name ?? 'Selecciona la imagen del comprobante (png/jpg)' }}</small>
                        </span>
                        <div v-if="state.total !== 0" class="ac-file-container">
                            <Input
                                type="file"
                                id="comprobante_pago"
                                name="comprobante_pago"
                                @change="
                                    form.comprobante_pago = ($event.target as HTMLInputElement)?.files?.[0] ?? null;
                                    updateFileName();
                                "
                                accept="image/png, image/jpeg, image/jpg"
                                style="display: none"
                            />
                            <label for="comprobante_pago" class="ac-file-label" id="file-label-id">
                                <div v-if="isFileUploaded" class="flex items-center">
                                    <span class="text-sm">{{ uploadedFileName }}</span>
                                    <Check></Check>
                                </div>
                                <div v-else class="flex items-center">
                                    <i class="fa-regular fa-folder" style="padding-right: 5px"></i>
                                    <span class="text-xs text-black dark:text-white">SUBIR ARCHIVO</span>
                                </div>
                            </label>
                        </div>
                        <Button
                            class="md:ml-1 ac-submit-button cursor-pointer"
                            :class="{ 'opacity-25': form.processing || form.loading }"
                            :disabled="form.processing || form.loading"
                            @click="submitForm"
                        >
                            <span v-if="!form.loading">{{ state.total === 0 ? 'Realizar Inscripción' : 'Procesar Pago' }}</span>
                            <span v-else>Cargando...</span>
                        </Button>
                        <div v-if="state.total !== 0" class="text-beige flex flex-col items-center">
                            <span class="text-sm text-black dark:text-beige">Es necesario realizar el deposito a la cuenta: </span>
                            <span class="text-xs text-black dark:text-beige">{{ billingData.bank }} - Cuenta de {{ billingData.type }}</span>
                            <span class="text-xs text-black dark:text-beige">{{ billingData.account }} </span>
                            <span class="text-xs text-black dark:text-beige">Titular: {{ billingData.name }}</span>
                            <span class="text-xs text-black dark:text-beige">CI: {{ billingData.ci }}</span>
                        </div>
                    </form>
                </div>
                <div v-else class="py-4 text-center">
                    <span class="ac-empty-message"
                        >No tienes items agregados al carrito aún
                        <Link href="/" class="ac-link">Volver al Inicio</Link>
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
/* Assassin's Creed Container */
.ac-container {
    border: 1px solid var(--color-gray);
    border-radius: 0;
    box-shadow: 0 0 20px rgba(226, 217, 202, 0.1);
    overflow: hidden;
    position: relative;
    padding: 1.5rem;
}

.ac-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, transparent, var(--color-wine), transparent);
}

/* Header */
.ac-header {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid var(--color-gray);
}

.ac-title {
    color: var(--color-wine);
    font-size: 1rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 2px;
    text-align: center;
    margin-top: 0rem;
    text-shadow: 0 0 5px rgba(226, 217, 202, 0.3);
}

/* Game Items */
.divide-ac-gray {
    border-color: var(--color-gray);
}

.ac-image-frame {
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    border: 1px solid var(--color-wine);
    z-index: 1;
    pointer-events: none;
    transition: all 0.3s ease;
}

.ac-image {
    position: relative;
    z-index: 0;
    transition: all 0.3s ease;
    filter: grayscale(30%);
}

li:hover .ac-image {
    filter: grayscale(0%);
}

li:hover .ac-image-frame {
    border-color: var(--color-beige);
    box-shadow: 0 0 8px rgba(226, 217, 202, 0.3);
}

.ac-game-title {
    position: relative;
    display: inline-block;
    transition: all 0.3s ease;
}

.ac-game-title::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 0;
    height: 1px;
    background-color: var(--color-wine);
    transition: width 0.3s ease;
}

li:hover .ac-game-title::after {
    width: 100%;
}

/* Buttons */
.ac-button {
    position: relative;
    background-color: transparent;
    color: var(--color-beige);
    border: 1px solid var(--color-gray);
    padding: 0.2rem 0.2rem;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    cursor: pointer;
    overflow: hidden;
    transition: all 0.3s ease;
}

.ac-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(226, 217, 202, 0.1), transparent);
    transition: left 0.5s ease;
}

.ac-button:hover::before {
    left: 100%;
}

.ac-button-edit {
    border-color: var(--color-gray-light);
}

.ac-button-create {
    border-color: var(--color-wine);
}

.ac-button:hover {
    background-color: rgba(60, 60, 54, 0.3);
    border-color: var(--color-beige);
}

.ac-button-text {
    position: relative;
    z-index: 1;
}

.ac-price {
    font-family: 'Courier New', monospace;
    font-weight: 700;
    text-shadow: 0 0 5px rgba(226, 217, 202, 0.3);
}

.ac-delete-button {
    background: transparent;
    border: none;
    color: var(--color-gray-light);
    cursor: pointer;
    transition: all 0.3s ease;
}

.ac-delete-wrapper {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.ac-delete-icon {
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.ac-delete-message {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    opacity: 50;
    transform: translateX(-10px);
    transition: all 0.3s ease;
}

.ac-delete-button:hover .ac-delete-message {
    opacity: 1;
    transform: translateX(0);
    color: var(--color-wine);
}

/* Modal */
.ac-modal {
    background-color: var(--color-black);
    border: 1px solid var(--color-gray);
    color: var(--color-beige);
}

.ac-modal-title {
    color: var(--color-beige);
    font-size: 1.25rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-bottom: 1rem;
    text-align: center;
    position: relative;
}

.ac-modal-title::after {
    content: '';
    position: absolute;
    bottom: -0.5rem;
    left: 50%;
    transform: translateX(-50%);
    width: 50px;
    height: 1px;
    background-color: var(--color-wine);
}

.ac-input {
    background-color: rgba(60, 60, 54, 0.3);
    border: 1px solid var(--color-gray);
    color: var(--color-beige);
    transition: all 0.3s ease;
}

.ac-input:focus {
    border-color: var(--color-beige);
    box-shadow: 0 0 0 2px rgba(226, 217, 202, 0.1);
}

.ac-member-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.ac-member-item {
    background-color: rgba(60, 60, 54, 0.5);
    border: 1px solid var(--color-gray);
    color: var(--color-beige);
    padding: 0.25rem 0.5rem;
    border-radius: 0;
    font-size: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.ac-member-item:hover {
    background-color: rgba(114, 33, 29, 0.3);
    border-color: var(--color-wine);
}

.ac-remove-member {
    background: transparent;
    border: none;
    color: var(--color-gray-light);
    cursor: pointer;
    font-size: 1rem;
    line-height: 1;
    transition: all 0.3s ease;
}

.ac-remove-member:hover {
    color: var(--color-wine);
}

.ac-primary-button {
    background-color: var(--color-wine) !important;
    border: none !important;
    color: var(--color-beige) !important;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
    transition: all 0.3s ease !important;
}

.ac-primary-button:hover {
    background-color: rgba(114, 33, 29, 0.8) !important;
    box-shadow: 0 0 10px rgba(114, 33, 29, 0.5) !important;
}

/* Total and Form */
.ac-total-container {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 1rem;
    margin: 0.5rem 0;
    padding: 0.6rem;
    border-left: 3px solid var(--color-wine);
}

.ac-total-amount {
    font-family: 'Courier New', monospace;
    font-weight: 700;
    position: relative;
    display: inline-block;
}

.ac-total-amount::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 100%;
    height: 1px;
    background-color: var(--color-wine);
}

.ac-form-container {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr;
    grid-gap: 10px;
    margin-top: 1.5rem;
}

@media screen and (max-width: 850px) {
    .ac-form-container {
        grid-template-columns: 1fr;
    }
}

.ac-cart-input {
    background-color: rgba(60, 60, 54, 0.3);
    border: 1px solid var(--color-gray);
    color: var(--color-beige);
    padding: 0.25rem 1rem;
    height: 40px;
    border-radius: 0;
    transition: all 0.3s ease;
}

.ac-cart-input:focus {
    border-color: var(--color-beige);
    box-shadow: 0 0 0 2px rgba(226, 217, 202, 0.1);
    outline: none;
}

.ac-cart-input::placeholder {
    color: var(--color-gray-light);
}

.ac-file-container {
    position: relative;
    height: 50px;
}

.ac-file-label {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 80%;
    background-color: rgba(60, 60, 54, 0.3);
    border: 1px solid var(--color-gray);
    color: var(--color-beige);
    cursor: pointer;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 0.75rem;
    transition: all 0.3s ease;
    overflow: hidden;
}

.ac-file-label:hover {
    background-color: rgba(114, 33, 29, 0.3);
    border-color: var(--color-wine);
}

.ac-submit-button {
    background-color: var(--color-wine) !important;
    border: none !important;
    color: var(--color-beige) !important;
    height: 40px;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
    transition: all 0.3s ease !important;
}

.ac-submit-button:hover {
    background-color: rgba(114, 33, 29, 0.8) !important;
    box-shadow: 0 0 10px rgba(114, 33, 29, 0.5) !important;
}

.ac-account-info {
    grid-column: 1 / -1;
    margin-top: 0.5rem;
    padding: 0.5rem;
    background-color: rgba(60, 60, 54, 0.2);
    border-left: 3px solid var(--color-wine);
    font-style: italic;
    color: var(--color-beige);
}

/* Empty cart message */
.ac-empty-message {
    color: var(--color-gray-light);
    display: block;
    text-align: center;
    padding: 2rem 0;
}

.ac-link {
    color: var(--color-wine);
    text-decoration: none;
    position: relative;
    margin-left: 0.5rem;
    transition: all 0.3s ease;
}

.ac-link::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 0;
    height: 1px;
    background-color: var(--color-beige);
    transition: width 0.3s ease;
}

.ac-link:hover {
    color: var(--color-beige);
}

.ac-link:hover::after {
    width: 100%;
}

/* Responsive adjustments */
@media screen and (max-width: 640px) {
    .part-1 {
        flex-direction: column;
        margin: 0 auto;
        text-align: center;
    }

    .ac-image {
        width: 60px;
        height: 60px;
        margin-bottom: 10px;
    }

    .ac-image-frame {
        top: -4px;
        left: -4px;
        right: -4px;
        bottom: -4px;
    }
}

/* Assassin's Creed themed animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(114, 33, 29, 0.4);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(114, 33, 29, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(114, 33, 29, 0);
    }
}

li {
    animation: fadeIn 0.5s ease forwards;
    animation-delay: calc(var(--index, 0) * 0.1s);
}

.ac-primary-button:focus,
.ac-submit-button:focus {
    animation: pulse 1.5s infinite;
}
</style>
