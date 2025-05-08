<!-- src/Components/JuegosIndividuales.vue -->
<template>
    <div class="m-28">
        <h2 class="section-title">Listado Juegos Individuales</h2>
        <!-- <div class="help-text mb-8">Selecciona los juegos para añadir a tú carrito...</div> -->
        <!-- Depuración: Mostrar el array juegos -->
        <div class="card_container">
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
                    <!-- <a
                        class="mt-5 px-2 py-1 bg-[#1c1c1a] text-[#e2d9ca] rounded border border-[#7c7c72] hover:bg-[#72211d] hover:border-[#e2d9ca] hover:shadow-[0_0_15px_rgba(114,33,29,0.5)] transition-all duration-500 text-xs uppercase tracking-wider relative overflow-hidden group/btn"
                        :href="`/pdf/${juego.img_id}.pdf`"
                        target="_blank"
                    >
                        <span class="relative z-10">Ver Reglamento</span>
                        <span
                            class="absolute inset-0 bg-gradient-to-r from-[#72211d]/80 to-[#72211d] transform translate-y-full group-hover/btn:translate-y-0 transition-transform duration-500"
                        ></span>
                    </a> -->
                </div>
            </div>
        </div>
        <h2 class="section-title">Listado Juegos Grupales</h2>
        <!-- <div class="help-text mb-8">Selecciona algún juego grupal, únicamente es necesario la inscripción del capitan...</div> -->
        <div class="card_container">
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
                    <!-- <a
                        class="mt-5 px-2 py-1 bg-[#1c1c1a] text-[#e2d9ca] rounded border border-[#7c7c72] hover:bg-[#72211d] hover:border-[#e2d9ca] hover:shadow-[0_0_15px_rgba(114,33,29,0.5)] transition-all duration-500 text-xs uppercase tracking-wider relative overflow-hidden group/btn"
                        :href="`/pdf/${juego.img_id}.pdf`"
                        target="_blank"
                    >
                        <span class="relative z-10">Ver Reglamento</span>
                        <span
                            class="absolute inset-0 bg-gradient-to-r from-[#72211d]/80 to-[#72211d] transform translate-y-full group-hover/btn:translate-y-0 transition-transform duration-500"
                        ></span>
                    </a> -->
                </div>
            </div>
        </div>
        <AlertComponent v-if="isAlertVisible" :message="alertMessage" />
    </div>
</template>

<script setup lang="ts">
import { ref, watchEffect } from 'vue';
import CardCheckbox from '../../CardCheckbox.vue';

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

const isAlertVisible = ref(false);
const alertMessage = ref('');

const juegosInscritos = ref(JSON.parse(localStorage.getItem('juegosInscritos') || '[]'));
const numJuegosSeleccionados = ref(juegosInscritos.value.length);

watchEffect(() => {
    const local = JSON.parse(localStorage.getItem('juegosInscritos') || '[]');
    juegosInscritos.value = local;
    numJuegosSeleccionados.value = juegosInscritos.value.length;
});

const showAlert = (gameName: string, gameId: number) => {
    const juegos = JSON.parse(localStorage.getItem('juegosInscritos') || '[]') as { id: number }[];
    const index = juegos.findIndex((j: { id: number }) => j.id === gameId);

    alertMessage.value = index > -1 ? `Se agregó el juego ${gameName} al carrito.` : `Se eliminó el juego ${gameName} del carrito.`;

    isAlertVisible.value = true;
    setTimeout(() => (isAlertVisible.value = false), 3000);
};

const handleSelectionChange = (selected: boolean) => {
    if (selected) {
        numJuegosSeleccionados.value++;
    } else {
        numJuegosSeleccionados.value--;
    }
};
</script>

<style scoped>
.section-title {
    position: relative;
    font-size: 2.5rem;
    font-weight: bold;
    color: #72211d;
    text-align: center;
    font-family: 'Times New Roman', serif;
    letter-spacing: 2px;
    text-transform: uppercase;
    padding-bottom: 0.5rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
    transform-style: preserve-3d;
    perspective: 1000px;
}
.help-text {
    position: relative;
    display: inline-block;
    animation: title-glow 3s ease-in-out infinite;
    color: #e2d9ca;
}
.card_container {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
}
</style>
