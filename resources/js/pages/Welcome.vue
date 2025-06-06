<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import logoLight from '@/../../public/images/Logo2025black.webp';
import logoDark from '@/../../public/images/Logo2025white.webp';
import Sponsors from '@/components/welcome/Sponsors.vue';
import Footer from '@/components/welcome/navigation/Footer.vue';
import Navbar from '@/components/welcome/navigation/Navbar.vue';
import JuegosIndividuales from '@/components/welcome/games/IndividualGames.vue';
import { ref } from 'vue';
import type { Ads } from '@/types/api/Ad.type';

defineProps({
    juegosIndividual: {
        type: Array as () => {
            id: number;
            nombre: string;
            costo_inscripcion: number;
            img_id: string;
            estaInscrito: boolean;
            reglamentos_pdf: string;
            active: boolean;
        }[],
        default: () => [],
    },
    juegosGrupo: {
        type: Array as () => {
            id: number;
            nombre: string;
            costo_inscripcion: number;
            img_id: string;
            estaInscrito: boolean;
            reglamentos_pdf: string;
            active: boolean;
        }[],
        default: () => [],
    },
    sponsors: {
        type: Array as () => { id: number; url_pagina: string; url_imagen: string; nombre: string; active: boolean }[],
        default: () => [],
    },
    ads: {
        type: Array as () => Ads[],
        default: () => [],
    },
});

// Track selected games count
const selectedGamesCount = ref(0);

// Update the count when children emit changes
const updateSelectedCount = (count: number) => {
    selectedGamesCount.value = count;
};

// Get inscribed games from localStorage for the navbar
const getJuegosInscritos = () => {
    return JSON.parse(localStorage.getItem('juegosInscritos') || '[]');
};
</script>

<template>
    <Head title="Bienvenidos" />
    <div
        id="nav"
        class="flex min-h-screen flex-col items-center justify-center bg-beige bg-[url('/public/images/texture.webp')] dark:bg-none pb-6 text-[#1b1b18] lg:pb-8 dark:bg-black"
    >
        <Navbar :ads="ads" :numJuegosSeleccionados="selectedGamesCount" :juegosInscritos="getJuegosInscritos()" />
        <div class="my-8 flex flex-col items-center justify-center opacity-100 transition-opacity duration-750 starting:opacity-0">
            <picture class="select-none pointer-events-none">
                <source :srcset="logoLight" media="(prefers-color-scheme: dark)" />
                <img class="w-98 mx-auto" :src="logoDark" alt="Logo" />
            </picture>
            <span class="font-cinzel text-wine dark:text-beige text-2xl select-none">JUNIO 3, 4 y 5</span>
        </div>
        <JuegosIndividuales :juegos="juegosIndividual" :juegosGrupo="juegosGrupo" @update-selected-count="updateSelectedCount" />
        <Sponsors :sponsors="sponsors" />
        <Footer />
    </div>
</template>
