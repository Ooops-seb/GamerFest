<script setup lang="ts">
import { Button } from '@/components/ui/button';
import icon from '@/../../public/images/Icon2025black.webp';
import {
    Drawer,
    DrawerClose,
    DrawerContent,
    DrawerDescription,
    DrawerFooter,
    DrawerHeader,
    DrawerTitle,
    DrawerTrigger,
} from '@/components/ui/drawer';

import { Ads } from '@/types/api/Ad.type';
import Carousel from '../ui/carousel/Carousel.vue';
import CarouselContent from '../ui/carousel/CarouselContent.vue';
import CarouselItem from '../ui/carousel/CarouselItem.vue';
import Card from '../ui/card/Card.vue';
import CardContent from '../ui/card/CardContent.vue';
import CarouselPrevious from '../ui/carousel/CarouselPrevious.vue';
import CarouselNext from '../ui/carousel/CarouselNext.vue';
import { CarouselApi } from '../ui/carousel';
import { ref, watch, onMounted, onBeforeUnmount, computed } from 'vue';
import { watchOnce } from '@vueuse/core';
import CardHeader from '../ui/card/CardHeader.vue';
import CardDescription from '../ui/card/CardDescription.vue';

const props = defineProps<{
    ads: Ads[];
    open?: boolean;
}>();

const emit = defineEmits(['update:open']);

const emblaMainApi = ref<CarouselApi>();
const emblaThumbnailApi = ref<CarouselApi>();
const selectedIndex = ref(0);
const isOpen = ref(false);

let carouselInterval: ReturnType<typeof setInterval> | null = null;

// Sync prop with local state
watch(
    () => props.open,
    (val) => {
        if (typeof val === 'boolean') isOpen.value = val;
    },
    { immediate: true },
);

// Emit when local state changes (e.g., Drawer closes)
watch(isOpen, (val) => {
    emit('update:open', val);
    if (!val && carouselInterval) clearInterval(carouselInterval);
    if (val && !carouselInterval) {
        carouselInterval = setInterval(() => {
            if (props.ads.length > 1 && isOpen.value) {
                nextAd();
            }
        }, 5000);
    }
});

function onSelect() {
    if (!emblaMainApi.value || !emblaThumbnailApi.value) return;
    selectedIndex.value = emblaMainApi.value.selectedScrollSnap();
    emblaThumbnailApi.value.scrollTo(emblaMainApi.value.selectedScrollSnap());
}

function onThumbClick(index: number) {
    if (!emblaMainApi.value || !emblaThumbnailApi.value) return;
    emblaMainApi.value.scrollTo(index);
}

function nextAd() {
    if (!emblaMainApi.value) return;
    const total = props.ads.length;
    const next = (emblaMainApi.value.selectedScrollSnap() + 1) % total;
    emblaMainApi.value.scrollTo(next);
}

onMounted(() => {
    if (carouselInterval) clearInterval(carouselInterval);
    carouselInterval = setInterval(() => {
        if (props.ads.length > 1 && isOpen.value) {
            nextAd();
        }
    }, 5000);
});

onBeforeUnmount(() => {
    if (carouselInterval) clearInterval(carouselInterval);
});

watchOnce(emblaMainApi, (emblaMainApi) => {
    if (!emblaMainApi) return;

    onSelect();
    emblaMainApi.on('select', onSelect);
    emblaMainApi.on('reInit', onSelect);
});

const filteredAds = computed(() => props.ads.filter((ad) => ad.active));
</script>

<template>
    <Drawer v-model:open="isOpen">
        <DrawerTrigger as-child>
            <button
                class="cursor-pointer dark:text-beige hover:text-wine px-4 py-2 text-sm font-cinzel font-medium transition-colors duration-200 border-b-2 border-transparent hover:border-wine flex items-center"
            >
                Anuncios
            </button>
        </DrawerTrigger>
        <DrawerContent>
            <div class="mx-auto w-full max-w-sm">
                <DrawerHeader>
                    <DrawerTitle>Anuncios</DrawerTitle>
                    <DrawerDescription>Aquí encontrarás anuncios importantes y novedades para los días del evento.</DrawerDescription>
                </DrawerHeader>
                <div class="p-4 pb-0">
                    <div class="w-full sm:w-auto">
                        <Carousel class="relative w-full max-w-xs" @init-api="(val) => (emblaMainApi = val)">
                            <CarouselContent>
                                <CarouselItem v-for="(ad, index) in filteredAds" :key="index">
                                    <div class="p-1">
                                        <Card>
                                            <CardHeader>{{ ad.title }}</CardHeader>
                                            <CardDescription v-if="ad.description" class="px-4 text-wrap">{{ ad.description }}</CardDescription>
                                            <CardContent class="flex aspect-square items-center justify-center p-6">
                                                <img :src="ad.image_url ?? icon" class="w-64" :alt="ad.id.toString()" />
                                            </CardContent>
                                        </Card>
                                    </div>
                                </CarouselItem>
                            </CarouselContent>
                            <CarouselPrevious />
                            <CarouselNext />
                        </Carousel>

                        <Carousel class="relative w-full max-w-xs" @init-api="(val) => (emblaThumbnailApi = val)">
                            <CarouselContent class="flex gap-1 ml-0">
                                <CarouselItem
                                    v-for="(ad, index) in filteredAds"
                                    :key="index"
                                    class="pl-0 basis-1/4 cursor-pointer"
                                    @click="onThumbClick(index)"
                                >
                                    <div class="p-1" :class="index === selectedIndex ? '' : 'opacity-50'">
                                        <Card>
                                            <CardContent class="flex aspect-square items-center justify-center p-2">
                                                <img :src="ad.image_url ?? icon" :alt="ad.id.toString()" class="w-32" />
                                            </CardContent>
                                        </Card>
                                    </div>
                                </CarouselItem>
                            </CarouselContent>
                        </Carousel>
                    </div>
                </div>
                <DrawerFooter>
                    <DrawerClose as-child>
                        <Button variant="outline" class="cursor-pointer"> Cerrar </Button>
                    </DrawerClose>
                </DrawerFooter>
            </div>
        </DrawerContent>
    </Drawer>
</template>
