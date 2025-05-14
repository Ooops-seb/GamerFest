<script setup lang="ts">
import AppSidebarLayout from '@/layouts/app/AppSidebarLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link as InertiaLink } from '@inertiajs/vue3';

interface Inscripcion {
    img_id: string;
    juego_nombre: string;
    tipo: string;
    equipo_nombre?: string;
    estado_pago: string;
}
defineProps({
    role: {
        type: Boolean,
        required: true,
    },
    inscripciones: {
        type: Array as () => Inscripcion[],
        required: true,
        default: () => [], // Valor predeterminado
    },
});
</script>

<template>
    <Head title="Mis Inscripciones" />
    <AppSidebarLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-beige leading-tight tracking-wide">Juegos Inscritos</h2>
        </template>
        <div v-if="inscripciones.length > 0" class="custom-width mx-auto px-4 sm:px-8">
            <div class="py-12 animate__animated animate__fadeInUp">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-6">
                    <div
                        v-for="(inscripcion, index) in inscripciones"
                        :key="index"
                        class="bg-gray border border-gray-light/20 overflow-hidden rounded-lg shadow-lg transition-all duration-300 hover:shadow-xl hover:scale-[1.02] hover:border-beige/40"
                    >
                        <div class="p-5 bg-black dark:bg-gra">
                            <div class="flex items-center">
                                <div
                                    class="relative overflow-hidden rounded-full border-2 border-beige/30 transition-all duration-300 hover:border-wine"
                                >
                                    <img
                                        :src="'/images/' + inscripcion.img_id + '.jpg'"
                                        alt="imagen del juego"
                                        class="w-16 h-16 rounded-full object-cover transition-transform duration-500 hover:scale-110"
                                    />
                                </div>
                                <div class="ml-5">
                                    <h2 class="text-lg leading-7 font-bold text-gray-100 tracking-wide">{{ inscripcion.juego_nombre }}</h2>
                                    <p class="mt-1 text-sm text-gray-200 flex items-center">
                                        <span class="inline-block w-2 h-2 rounded-full bg-gray-100 mr-2"></span>
                                        Modalidad: {{ inscripcion.tipo }}
                                    </p>
                                    <p v-if="inscripcion.tipo === 'Grupal'" class="mt-1 text-sm text-gray-light flex items-center">
                                        <span class="inline-block w-2 h-2 rounded-full bg-beige/70 mr-2"></span>
                                        Equipo: {{ inscripcion.equipo_nombre }}
                                    </p>
                                    <p
                                        :class="{
                                            'text-green-400': inscripcion.estado_pago === 'verificado',
                                            'text-wine': inscripcion.estado_pago === 'pendiente',
                                        }"
                                        class="mt-2 text-sm font-medium flex items-center"
                                    >
                                        <span class="text-gray-200 mr-2">Verificación pago:</span>
                                        <span
                                            class="px-2 py-0.5 rounded-full text-xs"
                                            :class="{
                                                'bg-green-400/20 text-green-400': inscripcion.estado_pago === 'verificado',
                                                'bg-wine/20 text-beige': inscripcion.estado_pago === 'pendiente',
                                                'bg-beige/20 text-beige':
                                                    inscripcion.juego_nombre == 'Concurso de Videojuego' ||
                                                    inscripcion.juego_nombre == 'Concurso de Cosplay',
                                            }"
                                        >
                                            {{
                                                inscripcion.juego_nombre == 'Concurso de Videojuego' ||
                                                inscripcion.juego_nombre == 'Concurso de Cosplay'
                                                    ? 'No aplica'
                                                    : inscripcion.estado_pago
                                            }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="custom-width mx-auto py-12 px-4 sm:px-8 text-center">
            <div class="bg-gray p-8 rounded-lg border border-gray-light/20 shadow-lg transition-all duration-300">
                <span class="text-beige block mb-4">No tienes items agregados al carrito aún</span>
                <InertiaLink
                    href="/"
                    class="text-wine hover:text-beige transition-colors duration-300 inline-block border-b border-wine hover:border-beige"
                >
                    Volver al Inicio
                </InertiaLink>
            </div>
        </div>
    </AppSidebarLayout>
</template>

<style scoped>
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

/* Animaciones adicionales */
@keyframes pulse-subtle {
    0%,
    100% {
        opacity: 1;
    }
    50% {
        opacity: 0.8;
    }
}

.animate-pulse-subtle {
    animation: pulse-subtle 3s infinite;
}
</style>
