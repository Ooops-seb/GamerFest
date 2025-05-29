<template>
  <Loader2Icon :class="['animate-spin', sizeClass, colorClass]" />
</template>

<script setup lang="ts">
import { Loader2Icon } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps({
  size: { type: String, default: 'md' }, // md, sm, lg
  color: { type: String, default: 'text-wine' },
});

const sizeClass = computed(() => {
  if (props.size === 'sm') return 'w-4 h-4';
  if (props.size === 'lg') return 'w-8 h-8';
  return 'w-5 h-5';
});
const colorClass = computed(() => props.color);
</script>
