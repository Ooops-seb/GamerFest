<script setup lang="ts">
import AppSidebarLayout from '@/layouts/app/AppSidebarLayout.vue';
import InputError from '@/components/InputError.vue';
import Swal from 'sweetalert2';
import InputLabel from '@/components/InputLabel.vue';
import TextInput from '@/components/TextInput.vue';
import { ref, onMounted } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import Modal from '@/components/Modal.vue';
import axios from 'axios';

const props = defineProps({
    user_id: {
        type: Number,
        default: 0, // Provide a default value to avoid undefined
    },
    role: {
        type: Boolean,
    },
});

const miembros = ref<string[]>([]);
const equipos = ref<{ id_juego: number; id: number; nombre_equipo: string; miembros: string }[]>([]);
const showModal = ref(false);

const formEquipo = useForm({
    id_equipo: '',
    id_juego: '',
    user_id: '',
    nombre_equipo: '',
    miembro: '',
    processing: false, // añadir esta propiedad si no existe
});

// Adiciona esta línea al inicio del script
const numeroMaxJugadores = 5;

// Modifica la función addMember así:
const addMember = () => {
    if (formEquipo.miembro && miembros.value.length < numeroMaxJugadores) {
        miembros.value.push(formEquipo.miembro);
        formEquipo.miembro = '';
    } else if (miembros.value.length >= numeroMaxJugadores) {
        // Muestra un mensaje usando sweetalert2 si se alcanza el límite de miembros
        Swal.fire({
            icon: 'warning',
            title: '¡Atención!',
            text: `El número máximo de miembros es ${numeroMaxJugadores}.`,
            showConfirmButton: true,
            background: '#1a1a1a',
            color: '#fff',
            confirmButtonText: 'Aceptar',
            customClass: {
                confirmButton: 'bg-wine text-white hover:bg-wine/80',
            },
        });
    }
};

// Agrega la siguiente función para eliminWar miembros
const removeMember = (index: number) => {
    miembros.value.splice(index, 1);
};

// Función para cerrar el modal
const closeModal = () => {
    showModal.value = false;
};

const updateTeam = async (event: Event) => {
    event.preventDefault();
    const id = formEquipo.id_equipo;
    const nombre_equipo = formEquipo.nombre_equipo;
    const miembros_list = miembros.value;
    const user_id = (formEquipo.user_id = props.user_id !== null ? props.user_id.toString() : '');

    const requestData = {
        id,
        id_juego: formEquipo.id_juego, // Provide an initializer for id_juego
        nombre_equipo,
        user_id,
        miembros: JSON.stringify(miembros_list),
    };

    try {
        // Aquí necesitas cambiar la URL a la que quieres enviar los datos del equipo.
        await axios.put(`/equipos/${id}`, requestData);

        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: 'El equipo ha sido actualizado correctamente.',
            showConfirmButton: true,
            background: '#1a1a1a',
            color: '#fff',
            confirmButtonText: 'Aceptar',
            customClass: {
                confirmButton: 'bg-wine text-white hover:bg-wine/80',
            },
        }).then((result) => {
            // Si el equipo fue creado correctamente, puedes redirigir a otra página o hacer algo más.
            if (result.isConfirmed) {
                // Limpiamos el formulario y la lista de miembros
                formEquipo.nombre_equipo = '';
                formEquipo.miembro = '';
                formEquipo.reset();
                miembros.value = [];
                closeModal();
                recuperarEquipos();
            }
        });
    } catch (error) {
        console.error('Error al actualizar el equipo:', error);
        Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: 'Hubo un problema al actualizar el equipo. Por favor, inténtalo de nuevo más tarde.',
            showConfirmButton: true,
            confirmButtonText: 'Aceptar',
            background: '#1a1a1a',
            color: '#fff',
            customClass: {
                confirmButton: 'bg-wine text-white hover:bg-wine/80',
            },
        });
    }
};

const recuperarEquipos = async () => {
    try {
        const user_id = props.user_id ? props.user_id : null;
        const formData = new FormData();

        formData.append('user_id', user_id !== null ? user_id.toString() : '');

        const response = await axios.post(`/equipo_por_juego`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });

        if (response.data) {
            equipos.value = response.data;
            return true;
        }

        return false;
    } catch (error) {
        console.error(error);
        return false;
    }
};

const handleMiembroKeyDown = (event: KeyboardEvent) => {
    if (event.key === 'Enter') {
        addMember();
        event.preventDefault();
    }
};

const openModal = (juegoId: number) => {
    const equipo = equipos.value.find((equipo) => equipo.id_juego === juegoId);

    if (equipo) {
        formEquipo.id_equipo = equipo.id.toString();
        formEquipo.id_juego = juegoId.toString();
        formEquipo.user_id = props.user_id !== null ? props.user_id.toString() : '';
        formEquipo.nombre_equipo = equipo.nombre_equipo;
        miembros.value = JSON.parse(equipo.miembros);
    } else {
        // Si no se encuentra el equipo, limpiar el formulario y los miembros
        formEquipo.id_juego = juegoId.toString();
        formEquipo.user_id = props.user_id !== null ? props.user_id.toString() : '';
        formEquipo.nombre_equipo = '';
        miembros.value = [];
    }

    showModal.value = true;
};

onMounted(async () => {
    await recuperarEquipos(); // Llama a la función recuperarEquipos al montar el componente
});
</script>

<template>
    <Head title="Mis Equipos" />

    <AppSidebarLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-white leading-tight tracking-wide">Equipos Registrados</h2>
        </template>

        <div class="py-12 animate__animated animate__fadeInUp">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden p-6">
                    <Modal :show="showModal" @close="closeModal">
                        <div class="p-6 bg-black border text-white">
                            <h3 class="modal-title text-xl font-bold mb-4 border-b border-beige/30 pb-2">Nuevo equipo</h3>
                            <form @submit="updateTeam($event)">
                                <div>
                                    <InputLabel for="nombre_equipo" value="Nombre del equipo" class="text-white" />
                                    <TextInput
                                        id="nombre_equipo"
                                        type="text"
                                        class="mt-1 block w-full bg-gray-light/40 border-gray-light/40 text-white focus:border-wine focus:ring-wine"
                                        v-model="formEquipo.nombre_equipo"
                                        required
                                    />
                                    <InputError class="mt-2" :message="formEquipo.errors.nombre_equipo" />
                                </div>

                                <div class="mt-4">
                                    <InputLabel for="miembro" value="Miembro" class="text-white" />
                                    <TextInput
                                        id="miembro"
                                        type="text"
                                        class="mt-1 block w-full bg-gray-light/20 border-gray-light/30 text-white focus:border-wine focus:ring-wine"
                                        ref="miembroInput"
                                        v-model="formEquipo.miembro"
                                        @keydown="handleMiembroKeyDown"
                                    />
                                    <span class="text-sm text-gray-300 mt-1 block"
                                        >(Ingresa el nombre real de cada integrante uno a la vez, da click a añadir miembro. Ej. John Doe)</span
                                    >
                                    <InputError class="mt-2" :message="formEquipo.errors.miembro" />
                                    <button
                                        type="button"
                                        :disabled="!formEquipo.miembro"
                                        class="flex justify-center items-center space-x-1 mt-3 bg-wine/80 hover:bg-wine text-white disabled:bg-gray-light/30 disabled:text-gray-200 disabled:cursor-not-allowed px-3 py-2 rounded-md transition-colors duration-300"
                                        @click="addMember"
                                    >
                                        <span>Añadir miembro</span>
                                        <i class="fa-solid fa-plus fa-xs flex items-center ml-2"></i>
                                    </button>
                                </div>

                                <div v-if="miembros.length" class="mt-6">
                                    <InputLabel value="Miembros del equipo" class="text-white mb-2" />
                                    <ul class="list-miembros flex flex-wrap gap-2">
                                        <li
                                            class="miembro-item flex items-center bg-wine text-white rounded-full px-3 py-1 transition-all duration-300 hover:bg-wine"
                                            v-for="(miembro, index) in miembros"
                                            :key="index"
                                        >
                                            <span>{{ miembro }}</span>
                                            <button
                                                class="ml-2 w-5 h-5 rounded-full bg-beige/10 hover:bg-beige/30 flex items-center justify-center transition-colors duration-300"
                                                @click.prevent="removeMember(index)"
                                            >
                                                &times;
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                                <div class="flex items-center justify-end mt-6">
                                    <button
                                        class="btn bg-white hover:bg-beige/80 text-black font-medium px-4 py-2 rounded-md transition-colors duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
                                        :disabled="formEquipo.processing"
                                        type="submit"
                                    >
                                        <span>Actualizar equipo</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </Modal>
                    <div v-if="equipos.length > 0" class="custom-width mx-auto px-4 sm:px-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div
                                class="card bg-gray-light/10 border border-gray-light/20 rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-all duration-300 hover:border-beige/30 hover:scale-[1.02]"
                                v-for="(equipo, index) in equipos"
                                :key="index"
                            >
                                <div class="card-header text-center bg-wine text-white py-3 px-4 font-bold text-lg">
                                    <i class="fa-solid fa-khanda mr-2"></i> {{ equipo.nombre_equipo }}
                                </div>
                                <div class="card-body p-4">
                                    <h5 class="card-title font-bold text-wine mb-3 flex items-center"><i class="fas fa-users mr-2"></i>Miembros:</h5>
                                    <ul class="space-y-2">
                                        <li
                                            class="ml-1 text-shadow-gray-300 flex items-center"
                                            v-for="(miembro, index2) in JSON.parse(equipo.miembros)"
                                            :key="index2"
                                        >
                                            <span class="w-2 h-2 rounded-full bg-gray mr-2"></span>
                                            {{ miembro }}
                                        </li>
                                    </ul>
                                </div>
                                <div class="action-container p-4 border-t border-gray-light/20">
                                    <button
                                        @click="openModal(equipo.id_juego)"
                                        class="button-editar-equipo w-full py-2 px-4 rounded-md bg-wine text-white hover:bg-wine/80 transition-colors duration-300 flex items-center justify-center"
                                    >
                                        <i class="fas fa-edit mr-2"></i> Editar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else class="custom-width mx-auto py-12 px-4 sm:px-8 text-center">
                        <div class="bg-gray-light/10 border border-gray-light/20 rounded-lg p-8 shadow-md">
                            <span class="text-beige block mb-4"> No tienes equipos creados aún, regístrate en juegos grupales </span>
                            <a
                                href="/"
                                class="text-wine hover:text-beige transition-colors duration-300 inline-block border-b border-wine hover:border-beige"
                            >
                                Volver al Inicio
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppSidebarLayout>
</template>

<!-- Estilos -->
<style>
:root {
    --color-black: #070706;
    --color-gray: #3c3c36;
    --color-gray-light: #7c7c72;
    --color-beige: #e2d9ca;
    --color-wine: #72211d;
}

.text-beige {
    color: var(--color-beige);
}

.text-wine {
    color: var(--color-wine);
}

.text-gray-light {
    color: var(--color-gray-light);
}

.bg-gray {
    background-color: var(--color-gray);
}

.bg-wine {
    background-color: var(--color-wine);
}

.border-beige {
    border-color: var(--color-beige);
}

.border-wine {
    border-color: var(--color-wine);
}

.custom-width {
    min-height: calc(80vh - 60px);
    height: calc(100vh - 60px);
}

.list-miembros {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.miembro-item {
    display: flex;
    align-items: center;
    background-color: var(--color-wine);
    opacity: 0.8;
    color: var(--color-beige);
    border-radius: 9999px;
    padding: 0.25rem 0.75rem;
    transition: all 0.3s ease;
}

.miembro-item:hover {
    opacity: 1;
    transform: translateY(-2px);
}

.miembro-item button {
    margin-left: 0.5rem;
    width: 1.25rem;
    height: 1.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 9999px;
    background-color: rgba(226, 217, 202, 0.1);
    transition: background-color 0.3s ease;
}

.miembro-item button:hover {
    background-color: rgba(226, 217, 202, 0.3);
}

/* Animaciones para las tarjetas */
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

.card {
    animation: fadeIn 0.5s ease forwards;
    animation-delay: calc(var(--index, 0) * 0.1s);
}

/* Estilos responsivos */
@media screen and (max-width: 850px) {
    .form-container {
        grid-template-columns: 1fr;
    }

    .btn {
        width: 100%;
        margin: 0;
    }

    .file-label {
        width: 100%;
        margin: 0;
    }

    .miembro-item {
        width: 100%;
        margin-bottom: 0.5rem;
    }
}
</style>
