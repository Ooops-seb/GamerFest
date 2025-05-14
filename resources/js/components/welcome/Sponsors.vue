<template>
    <section v-if="sponsors && sponsors.length > 0" id="sponsors" class="py-8 w-full text-beige">
        <div class="container mx-auto px-4">
            <!-- Título principal con estilo antiguo/pergamino -->
            <h1 class="text-3xl md:text-4xl font-bold text-center mb-12 text-wine tracking-wider relative font-cinzel">
                SPONSORS
                <span class="block h-1 w-24 bg-wine mx-auto mt-2"></span>
            </h1>

            <!-- Contenedor de sponsors con diseño de pergamino antiguo -->
            <div class="overflow-hidden mt-10 py-10 px-6">
                <!-- Elementos decorativos de esquina estilo Assassin's Creed -->
                <div class="absolute top-0 left-0 w-16 h-16 border-t border-l border-wine/70"></div>
                <div class="absolute bottom-0 right-0 w-16 h-16 border-b border-r border-wine/70"></div>
                <div class="absolute top-0 right-0 w-16 h-16 border-t border-r border-wine/70"></div>
                <div class="absolute bottom-0 left-0 w-16 h-16 border-b border-l border-wine/70"></div>

                <!-- Subtítulo con estilo antiguo -->
                <div class="text-center text-gray-light mb-12 font-cinzel font-light">Organizaciones que hacen posible este torneo</div>

                <!-- Grid de sponsors con nuevo layout -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-1 justify-items-center">
                    <div v-for="sponsor in sponsors" :key="sponsor.id" class="group">
                        <Link :href="sponsor.url_pagina" class="block transition-all duration-300 hover:scale-105">
                            <div class="sponsor-card">
                                <div class="sponsor-border"></div>
                                <div class="sponsor-content">
                                    <img
                                        :src="`/images/sponsors/${sponsor.url_imagen}`"
                                        :alt="sponsor.nombre"
                                        class="w-full h-auto relative z-10 transition-all duration-300 group-hover:brightness-110 mx-auto"
                                    />
                                </div>
                            </div>
                            <div
                                class="font-cinzel mb-2 text-center dark:text-white font-medium tracking-wide opacity-80 group-hover:opacity-100 transition-opacity duration-300"
                            >
                                {{ sponsor.nombre }}
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

interface Sponsor {
    id: number;
    url_pagina: string;
    url_imagen: string;
    nombre: string;
}

defineProps({
    sponsors: {
        type: Array as () => Sponsor[],
        default: () => [],
    },
});
</script>

<style>
@keyframes borderPulse {
    0%,
    100% {
        border-color: rgba(114, 33, 29, 0.6);
    }
    50% {
        border-color: rgba(114, 33, 29, 0.9);
    }
}

@keyframes subtleFade {
    0%,
    100% {
        opacity: 0.8;
    }
    50% {
        opacity: 1;
    }
}

:root {
    --color-black: #070706;
    --color-gray: #3c3c36;
    --color-gray-light: #9c9c98;
    --color-beige: #000000;
    --color-wine: #72211d;
}

/* Aplicando variables de color */
.text-beige {
    color: var(--color-beige);
}

.text-wine {
    color: var(--color-wine);
}

.bg-black {
    background-color: var(--color-black);
}

.border-gray-light {
    border-color: var(--color-gray-light);
}

.border-wine {
    border-color: var(--color-wine);
}

.text-gray-light {
    color: var(--color-gray-light);
}

.sponsor-card {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--color-gray);
    border: 2px solid var(--color-wine);
    width: 220px;
    height: 220px;
    padding: 0.3rem;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(114, 33, 29, 0.2);
    border-radius: 8px;
}

.sponsor-card:hover {
    animation: borderPulse 2s infinite;
    box-shadow: 0 8px 25px rgba(114, 33, 29, 0.3);
}

.sponsor-border {
    position: absolute;
    top: 2px;
    left: 2px;
    right: 2px;
    bottom: 2px;
    pointer-events: none;
    opacity: 0.5;
    transition: opacity 0.3s ease;
}

.sponsor-card:hover .sponsor-border {
    opacity: 0.1;
    animation: subtleFade 2s infinite;
}

.sponsor-content {
    position: relative;
    z-index: 10;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--color-black);
    padding: 0.1rem;
}
</style>
