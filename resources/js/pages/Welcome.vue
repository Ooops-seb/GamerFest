<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import logoLight from '@/../../public/images/Logo2025black.webp';
import logoDark from '@/../../public/images/Logo2025white.webp';
</script>

<template>
    <Head title="Bienvenidos"> </Head>
    <div
        class="flex min-h-screen flex-col items-center justify-center bg-beige bg-[url('/public/images/texture.webp')] dark:bg-none p-6 text-[#1b1b18] lg:p-8 dark:bg-[#070706]"
    >
        <div class="flex flex-col items-center justify-center opacity-100 transition-opacity duration-750 starting:opacity-0">
            <picture>
                <source :srcset="logoLight" media="(prefers-color-scheme: dark)" />
                <img class="w-98 mx-auto" :src="logoDark" alt="Logo" />
            </picture>
            <span class="font-cinzel text-wine dark:text-beige text-2xl">Próximamente en Junio</span>
        </div>
    </div>
</template>
