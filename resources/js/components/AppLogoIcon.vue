<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import logoLight from '@/../../public/favicon-light.ico';
import logoDark from '@/../../public/favicon-dark.ico';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <picture class="select-none pointer-events-none">
        <source :srcset="logoDark" media="(prefers-color-scheme: dark)" />
        <source :srcset="logoLight" media="(prefers-color-scheme: light)" />
        <img class="w-98 mx-auto" :src="logoLight" alt="Logo" />
    </picture>
</template>
