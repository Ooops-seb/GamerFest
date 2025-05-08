<template>
    <div
        :class="[
            'group relative overflow-hidden rounded-lg transition-all duration-700 ease-in-out',
            'bg-gradient-to-b from-[#0c0c0a] to-[#151512] border border-[#3c3c36]',
            'transform hover:-translate-y-2 hover:shadow-[0_0_25px_rgba(226,217,202,0.4)]',
            'cursor-pointer h-56 w-40 md:h-72 md:w-56',
            { 'border-[#72211d] shadow-[0_0_20px_rgba(114,33,29,0.6)]': selected && !estaInscrito },
        ]"
        @click="toggleSelection"
    >
        <!-- Efecto de fondo con símbolo de Assassin's Creed animado -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden">
            <div
                class="absolute inset-0 bg-center bg-no-repeat bg-contain opacity-5 transform group-hover:scale-110 transition-transform duration-1000 ease-in-out"
            ></div>

            <!-- Líneas de código/Animus flotantes -->
            <div class="absolute inset-0 overflow-hidden opacity-10">
                <div class="absolute top-0 left-0 w-full h-full flex flex-col gap-3 animate-float">
                    <div v-for="i in 10" :key="i" class="h-px bg-[#e2d9ca] w-full opacity-30"></div>
                </div>
            </div>
        </div>

        <!-- Borde decorativo con efecto de glitch -->
        <div
            class="absolute inset-0 border border-[#3c3c36] m-1.5 opacity-0 group-hover:opacity-100 transition-all duration-500 animate-glitch"
        ></div>

        <!-- Contenido principal con efecto parallax -->
        <div class="p-5 flex flex-col items-center h-full relative z-10">
            <!-- Contenedor de imagen con efecto de hover -->
            <div
                :class="[
                    'relative overflow-hidden rounded-md mb-4 border-2 transform group-hover:translate-y-[-5px]',
                    'border-[#3c3c36] group-hover:border-[#7c7c72]',
                    'transition-all duration-700 ease-out shadow-[0_5px_15px_rgba(0,0,0,0.3)]',
                    { 'border-[#72211d]': estaInscrito },
                ]"
            >
                <!-- Imagen con efecto de hover y filtro -->
                <img
                    :src="imageUrl"
                    :alt="gameName"
                    class="w-40 h-40 object-cover transition-all duration-700 ease-out"
                    :class="{
                        'filter grayscale brightness-75': estaInscrito,
                        'group-hover:scale-110 group-hover:filter group-hover:brightness-110': !estaInscrito,
                    }"
                />

                <!-- Overlay con efecto de viñeta -->
                <div
                    class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"
                ></div>

                <!-- Overlay de selección con animación pulsante -->
                <div v-if="selected && !estaInscrito" class="absolute inset-0 bg-[#72211d]/30 flex items-center justify-center backdrop-blur-sm">
                    <div class="w-14 h-14 border-2 border-[#e2d9ca] rounded-full flex items-center justify-center animate-pulse-slow">
                        <div class="w-10 h-10 bg-[#e2d9ca] rounded-full"></div>
                    </div>
                </div>

                <!-- Etiqueta de inscrito con estilo de sello y efecto de glitch -->
                <div v-if="estaInscrito" class="absolute inset-0 flex items-center justify-center">
                    <div
                        class="transform rotate-[-30deg] bg-[#72211d] text-[#e2d9ca] px-6 py-2 font-bold text-2xl tracking-wider shadow-lg border border-[#e2d9ca] animate-pulse-slow animate-glitch-text"
                    >
                        INSCRITO
                    </div>
                </div>
            </div>

            <!-- Detalles del juego con estilo de pergamino antiguo -->
            <div class="w-full text-center mt-2 transform group-hover:translate-y-[-5px] transition-transform duration-700 ease-out">
                <h3 class="text-[#e2d9ca] font-serif text-xl mb-2 group-hover:text-white transition-colors duration-500 tracking-wide">
                    {{ gameName }}
                </h3>
                <div class="flex items-center justify-center">
                    <p
                        :class="[
                            'text-sm font-medium tracking-wider',
                            'transition-all duration-500 ease-in-out',
                            Number(gamePrice) > 0 ? 'text-[#e2d9ca]' : 'text-[#7c7c72]',
                            'group-hover:text-[#d4af37] group-hover:drop-shadow-[0_0_8px_rgba(212,175,55,0.5)]',
                        ]"
                    >
                        {{ Number(gamePrice) > 0 ? '$' + gamePrice : 'Gratis' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Efecto de esquina doblada mejorado -->
        <div
            class="absolute -bottom-12 -right-12 w-24 h-24 bg-gradient-to-br from-[#3c3c36] to-[#1c1c1a] transform rotate-45 group-hover:from-[#72211d] group-hover:to-[#3c1210] transition-colors duration-700"
        ></div>

        <!-- Efecto de partículas flotantes (polvo) -->
        <div class="absolute inset-0 overflow-hidden opacity-0 group-hover:opacity-100 transition-opacity duration-1000 pointer-events-none">
            <div
                v-for="i in 8"
                :key="`dust-${i}`"
                :class="`absolute w-1 h-1 rounded-full bg-[#e2d9ca]/20 animate-float-particle`"
                :style="`left: ${Math.random() * 100}%; top: ${Math.random() * 100}%; animation-delay: ${Math.random() * 5}s; animation-duration: ${5 + Math.random() * 5}s`"
            ></div>
        </div>

        <!-- Efecto de iluminación dinámica -->
        <div
            class="absolute inset-0 bg-gradient-to-t from-[#72211d]/0 to-transparent opacity-0 group-hover:opacity-30 transition-opacity duration-700 mix-blend-overlay"
        ></div>
    </div>
</template>

<script lang="ts">
export default {
    name: 'CardCheckbox',
    props: {
        gameId: {
            type: Number,
            required: true,
        },
        gameName: {
            type: String,
            required: true,
        },
        gamePrice: {
            type: [Number, String],
            required: true,
        },
        imageUrl: {
            type: String,
            required: true,
        },
        imageId: {
            type: String,
            required: true,
        },
        estaInscrito: {
            type: Boolean,
            required: true,
        },
    },
    data() {
        return {
            selected: false,
        };
    },
    methods: {
        toggleSelection() {
            if (this.estaInscrito) {
                return;
            }

            this.selected = !this.selected;
            const juegosInscritos = JSON.parse(localStorage.getItem('juegosInscritos') || '[]');

            if (this.selected) {
                const juego = {
                    id: this.gameId,
                };
                juegosInscritos.push(juego);
            } else {
                const index = juegosInscritos.findIndex((juego: { id: number }) => juego.id === this.gameId);
                if (index > -1) {
                    juegosInscritos.splice(index, 1);
                }
            }

            localStorage.setItem('juegosInscritos', JSON.stringify(juegosInscritos));

            this.$emit('selectionChange', this.selected);
            this.$emit('showAlert', this.gameName, this.gameId);
        },
    },
    created() {
        const juegosInscritos = JSON.parse(localStorage.getItem('juegosInscritos') || '[]') as { id: number }[];
        const juegoSeleccionado = juegosInscritos.find((juego: { id: number }) => juego.id === this.gameId);

        this.selected = juegoSeleccionado !== undefined;
    },
};
</script>

<style>
@keyframes pulse-slow {
    0%,
    100% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.7;
        transform: scale(0.95);
    }
}

@keyframes float {
    0%,
    100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}

@keyframes float-particle {
    0% {
        transform: translateY(0) translateX(0);
        opacity: 0;
    }
    25% {
        opacity: 1;
    }
    75% {
        opacity: 0.8;
    }
    100% {
        transform: translateY(-100px) translateX(20px);
        opacity: 0;
    }
}

@keyframes glitch {
    0% {
        clip-path: inset(0 0 0 0);
    }
    2% {
        clip-path: inset(10% 0 10% 0);
        transform: translate(-2px);
    }
    4% {
        clip-path: inset(0 0 0 0);
        transform: translate(0);
    }
    6% {
        clip-path: inset(0 10% 0 10%);
        transform: translate(2px);
    }
    8% {
        clip-path: inset(0 0 0 0);
        transform: translate(0);
    }
    100% {
        clip-path: inset(0 0 0 0);
    }
}

@keyframes glitch-text {
    0% {
        text-shadow: 0 0 0 rgba(226, 217, 202, 0);
    }
    2% {
        text-shadow:
            -3px 0 2px rgba(226, 217, 202, 0.7),
            3px 0 2px rgba(114, 33, 29, 0.7);
        transform: translate(-2px) rotate(-32deg);
    }
    4% {
        text-shadow:
            3px 0 2px rgba(226, 217, 202, 0.7),
            -3px 0 2px rgba(114, 33, 29, 0.7);
        transform: translate(2px) rotate(-28deg);
    }
    6% {
        text-shadow: 0 0 0 rgba(226, 217, 202, 0);
        transform: translate(0) rotate(-30deg);
    }
    100% {
        text-shadow: 0 0 0 rgba(226, 217, 202, 0);
    }
}

.animate-pulse-slow {
    animation: pulse-slow 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

.animate-float {
    animation: float 6s ease-in-out infinite;
}

.animate-float-particle {
    animation: float-particle 8s ease-in-out infinite;
}

.animate-glitch {
    animation: glitch 10s ease-in-out infinite;
    animation-delay: 2s;
}

.animate-glitch-text {
    animation: glitch-text 8s ease-in-out infinite;
    animation-delay: 1s;
}
</style>
