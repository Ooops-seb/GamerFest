<template>
    <section v-if="sponsors && sponsors.length > 0" id="sponsors" class="py-16 w-full">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-wine text-2xl md:text-3xl font-bold font-cinzel tracking-wider mb-4">NUESTROS PATROCINADORES</h2>

            <div class="flex items-center justify-center w-4/5 mx-auto my-6">
                <div class="h-px bg-beige/30 flex-grow"></div>
                <div class="w-5 h-5 bg-wine transform rotate-45 mx-6 relative">
                    <div class="w-2.5 h-2.5 absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"></div>
                </div>
                <div class="h-px bg-beige/30 flex-grow"></div>
            </div>

            <div class="flex flex-wrap items-center justify-center gap-4 mt-8">
                <div
                    v-for="sponsor in sponsors"
                    :key="sponsor.id || sponsor.name"
                    class="flex flex-col items-center transition-transform duration-300 hover:-translate-y-1"
                >
                    <div
                        class="w-32 h-24 md:w-56 md:h-48 flex items-center justify-center mb-4 bg-black border border-gray-light rounded p-2 hover:border-wine"
                    >
                        <template v-if="sponsor.url_pagina">
                            <a
                                :href="sponsor.url_pagina"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="w-full h-full flex items-center justify-center"
                            >
                                <img
                                    :src="sponsor.url_imagen"
                                    :alt="sponsor.name"
                                    class="max-w-full max-h-full brightness-0 invert opacity-80 transition-opacity duration-300 hover:opacity-100"
                                />
                            </a>
                        </template>
                        <template v-else>
                            <img
                                :src="sponsor.url_imagen"
                                :alt="sponsor.name"
                                class="max-w-full max-h-full brightness-0 invert opacity-80 transition-opacity duration-300 hover:opacity-100"
                            />
                        </template>
                    </div>
                    <p class="text-beige text-sm font-cinzel font-semibold tracking-wide">
                        {{ sponsor.name }}
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup lang="ts">
import { ISponsors } from '@/types/api/Sponsors';
import { ref } from 'vue';

const sponsors = ref<ISponsors[]>([
    // {
    //     id: '1',
    //     name: 'EPIC GAMES',
    //     url_pagina: 'https://www.epicgames.com',
    //     url_imagen: 'https://www.electricianlicense.com/wp-content/uploads/2020/06/placeholder-img.jpg',
    // },
    // {
    //     id: '2',
    //     name: 'NVIDIA',
    //     url_pagina: 'https://www.nvidia.com',
    //     url_imagen: 'https://www.electricianlicense.com/wp-content/uploads/2020/06/placeholder-img.jpg',
    // },
    // {
    //     id: '3',
    //     name: 'RAZER',
    //     url_imagen: 'https://www.electricianlicense.com/wp-content/uploads/2020/06/placeholder-img.jpg',
    // },
    // {
    //     id: '4',
    //     name: 'ASUS ROG',
    //     url_pagina: 'https://rog.asus.com',
    //     url_imagen: 'https://www.electricianlicense.com/wp-content/uploads/2020/06/placeholder-img.jpg',
    // },
    // {
    //     id: '5',
    //     name: 'HYPERX',
    //     url_imagen: 'https://www.electricianlicense.com/wp-content/uploads/2020/06/placeholder-img.jpg',
    // },
    // {
    //     id: '6',
    //     name: 'STEELSERIES',
    //     url_pagina: 'https://steelseries.com',
    //     url_imagen: 'https://www.electricianlicense.com/wp-content/uploads/2020/06/placeholder-img.jpg',
    // },
]);
</script>
