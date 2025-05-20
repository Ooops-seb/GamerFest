<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const props = defineProps<{
    text?: string;
    linkText?: string;
    linkHref?: string;
}>();
</script>

<template>
    <div class="custom-width mx-auto py-12 px-4 sm:px-8 text-center">
        <div class="bg-gray-light/10 border border-gray-light/20 rounded-lg p-8 shadow-md">
            <span class="text-beige block mb-4">
                {{ props.text ?? 'No tienes equipos creados aún, regístrate en juegos grupales' }}
            </span>
            <Link
                :href="props.linkHref ?? '/'"
                class="text-wine hover:text-beige transition-colors duration-300 inline-block border-b border-wine hover:border-beige"
            >
                {{ props.linkText ?? 'Volver al Inicio' }}
            </Link>
        </div>
    </div>
</template>
