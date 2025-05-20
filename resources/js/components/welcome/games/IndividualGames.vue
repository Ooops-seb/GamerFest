<template>
    <div class="w-full my-12 mx-28 px-4 flex flex-col justify-center">
        <h2 class="mb-4 text-2xl md:text-4xl font-bold text-wine text-center font-cinzel tracking-[2px] uppercase pb-2 relative">
            Listado Juegos Individuales
        </h2>
        <!-- <div class="help-text mb-8">Selecciona los juegos para añadir a tú carrito...</div> -->
        <!-- Depuración: Mostrar el array juegos -->
        <div class="w-full flex flex-wrap gap-4 justify-center">
            <div v-for="juego in juegos" :key="juego.id">
                <div class="flex flex-col items-center mb-4">
                    <CardCheckbox
                        :gameId="juego.id"
                        :gameName="juego.nombre"
                        :gamePrice="juego.costo_inscripcion"
                        :imageUrl="`/images/${juego.img_id}.jpg`"
                        :imageId="`${juego.img_id}`"
                        :estaInscrito="juego.estaInscrito"
                        @selectionChange="handleSelectionChange"
                        @showAlert="showAlert"
                    />
                </div>
            </div>
        </div>
        <h2 class="mt-4 mx-2 text-2xl md:text-4xl font-bold text-wine text-center font-cinzel tracking-[2px] uppercase pb-2 relative">
            Listado Juegos Grupales
        </h2>
        <!-- <div class="help-text mb-8">Selecciona algún juego grupal, únicamente es necesario la inscripción del capitan...</div> -->
        <div class="w-full flex flex-wrap gap-4 justify-center">
            <div v-for="juego in juegosGrupo" :key="juego.id">
                <div class="flex flex-col items-center mb-4">
                    <CardCheckbox
                        :gameId="juego.id"
                        :gameName="juego.nombre"
                        :gamePrice="juego.costo_inscripcion"
                        :imageUrl="`/images/${juego.img_id}.jpg`"
                        :imageId="`${juego.img_id}`"
                        :estaInscrito="juego.estaInscrito"
                        @selectionChange="handleSelectionChange"
                        @showAlert="showAlert"
                    />
                </div>
            </div>
        </div>
        <Toaster />
    </div>
</template>

<script setup lang="ts">
import { ref, watchEffect } from 'vue';
import CardCheckbox from '../../CardCheckbox.vue';
import { toast } from 'vue-sonner';
import { Toaster } from '@/components/ui/sonner';
import { onMounted } from 'vue';

const emit = defineEmits(['updateSelectedCount']);

onMounted(() => {
    setTimeout(() => {
        toast('¡Mantente atento a las noticias!', {
            description: 'Pronto habrá novedades importantes para los participantes.',
        });
    }, 2000);
});

defineProps<{
    juegos: {
        id: number;
        nombre: string;
        costo_inscripcion: number;
        img_id: string;
        estaInscrito: boolean;
    }[];
    juegosGrupo: {
        id: number;
        nombre: string;
        costo_inscripcion: number;
        img_id: string;
        estaInscrito: boolean;
    }[];
}>();

const juegosInscritos = ref(JSON.parse(localStorage.getItem('juegosInscritos') || '[]'));
const juegosSeleccionados = ref<number[]>([]);
const numJuegosSeleccionados = ref(0);

watchEffect(() => {
    const local = JSON.parse(localStorage.getItem('juegosInscritos') || '[]');
    juegosInscritos.value = local;
});

const showAlert = (gameName: string, gameId: number) => {
    const juegos = JSON.parse(localStorage.getItem('juegosInscritos') || '[]') as { id: number }[];
    const index = juegos.findIndex((j: { id: number }) => j.id === gameId);

    if (index > -1) {
        toast('Juego añadido al carrito!', {
            description: `Haz agregado ${gameName}.`,
            actionButtonStyle: {
                backgroundColor: '#218838',
                color: '#e2d9ca',
            },
        });
    } else {
        toast('Juego removido del carrito!', {
            style: {
                backgroundColor: '#72211d',
                color: '#e2d9ca',
            },
            description: `Haz eliminado ${gameName}.`,
        });
    }
};

watchEffect(() => {
    emit('updateSelectedCount', numJuegosSeleccionados.value);
});

const handleSelectionChange = (selected: boolean, gameId: number) => {
    // This ONLY handles UI selection state, not cart registration
    if (selected) {
        numJuegosSeleccionados.value++;
        juegosSeleccionados.value.push(gameId);
    } else {
        numJuegosSeleccionados.value--;
        const index = juegosSeleccionados.value.indexOf(gameId);
        if (index > -1) {
            juegosSeleccionados.value.splice(index, 1);
        }
    }

    emit('updateSelectedCount', numJuegosSeleccionados.value);
};
</script>
