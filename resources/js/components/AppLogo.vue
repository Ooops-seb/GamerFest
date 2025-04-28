<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import pkg from '../../../package.json';
const version = pkg.version;
const appName = import.meta.env.VITE_APP_NAME || 'App';
</script>

<template>
    <div class="bg-sidebar-primary text-sidebar-primary-foreground flex aspect-square size-8 items-center justify-center rounded-md">
        <AppLogoIcon class="size-5 fill-current text-white dark:text-black" />
    </div>
    <div class="ml-1 grid flex-1 text-center">
        <span class="mb-0.5 truncate text-sm leading-none font-semibold">{{ appName }}</span>
        <small class="text-[8px] text-gray-400">{{ version }}</small>
    </div>
</template>
