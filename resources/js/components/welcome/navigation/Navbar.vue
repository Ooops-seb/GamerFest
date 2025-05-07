<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ChevronDown } from 'lucide-vue-next';
import { ref } from 'vue';
import { route } from 'ziggy-js';
import iconLight from '@/../../public/images/Icon2025black.webp';
import iconDark from '@/../../public/images/Icon2025white.webp';

const isMenuOpen = ref(false);
const openMobileDropdown = ref<string | null>(null);

const toggleMobileDropdown = (section: string) => {
    if (openMobileDropdown.value === section) {
        openMobileDropdown.value = null;
    } else {
        openMobileDropdown.value = section;
    }
};
defineProps({
    role: {
        type: Boolean,
    },
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
    numJuegosSeleccionados: {
        type: Number,
        required: true,
    },
    juegosInscritos: {
        type: Array,
        required: true,
    },
});

const navigationItems = [
    {
        name: 'INICIO',
        href: '#',
    },
    {
        name: 'EVENTOS',
        href: '#',
        subItems: [
            { name: 'Conferencias', href: '#' },
            { name: 'Exhibiciones', href: '#' },
            { name: 'Talleres', href: '#' },
            { name: 'Calendario', href: '#' },
        ],
    },
    {
        name: 'TORNEOS',
        href: '#',
        subItems: [
            { name: 'League of Legends', href: '#' },
            { name: 'Valorant', href: '#' },
            { name: 'CS:GO', href: '#' },
            { name: 'Fortnite', href: '#' },
        ],
    },
    { name: 'SPONSORS', href: '#sponsors' },
    { name: 'GALERÍA', href: '#' },
    { name: 'CONTACTO', href: '#contact' },
];
</script>

<template>
    <div class="dark:bg-black w-full">
        <!-- Barra superior con información del evento -->
        <div class="hidden lg:block dark:text-[#f5f5f0] text-black dark:bg-black border-b border-gray/10 dark:border-[#f5f5f0]/10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-10 text-xs">
                    <div class="flex items-center space-x-4">
                        <span class="font-cinzel"> <span class="text-[#c41e3a]">PRÓXIMAMENTE:</span> JUNIO</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a
                            href="https://www.facebook.com/gamerfest.ec"
                            target="_blank"
                            class="hover:text-[#c41e3a] transition-colors duration-200 flex items-center"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"
                                />
                            </svg>
                            Facebook
                        </a>
                        <a
                            href="https://www.instagram.com/gamerfest.ec"
                            target="_blank"
                            class="hover:text-[#c41e3a] transition-colors duration-200 flex items-center"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"
                                />
                            </svg>
                            Instagram
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navbar principal -->
        <header class="dark:bg-black border-b border-gray/10 dark:border-[#f5f5f0]/10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <a href="/" class="flex items-center group">
                            <picture class="select-none pointer-events-none mr-2">
                                <source :srcset="iconLight" media="(prefers-color-scheme: dark)" />
                                <img class="w-8 mx-auto" :src="iconDark" alt="Logo" />
                            </picture>
                            <div class="flex flex-col">
                                <span class="text-wine text-2xl font-bold font-cinzel tracking-wider">GAMERFEST</span>
                                <span class="dark:text-[#f5f5f0] text-black text-sm font-cinzel tracking-wider">2025</span>
                            </div>
                        </a>
                    </div>

                    <!-- Navegación para pantallas medianas y grandes -->
                    <nav class="hidden lg:flex space-x-1">
                        <template v-for="item in navigationItems" :key="item.name">
                            <Link
                                v-if="!item.subItems"
                                href="item.href"
                                class="dark:text-[#f5f5f0] hover:text-[#c41e3a] px-4 py-2 text-sm font-cinzel font-medium transition-colors duration-200 border-b-2 border-transparent hover:border-[#c41e3a]"
                            >
                                {{ item.name }}
                            </Link>
                            <div v-else class="relative group">
                                <button
                                    class="dark:text-[#f5f5f0] hover:text-[#c41e3a] px-4 py-2 text-sm font-cinzel font-medium transition-colors duration-200 border-b-2 border-transparent hover:border-[#c41e3a] flex items-center"
                                >
                                    {{ item.name }}
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 ml-1"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div
                                    class="absolute left-0 mt-2 w-48 bg-[#0a0a0a] border border-[#f5f5f0]/10 rounded shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50"
                                >
                                    <template v-for="subItem in item.subItems" :key="subItem.name">
                                        <a
                                            :href="subItem.href"
                                            class="block px-4 py-2 text-sm font-cinzel text-[#f5f5f0] hover:bg-[#c41e3a]/10 hover:text-[#c41e3a]"
                                        >
                                            {{ subItem.name }}
                                        </a>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </nav>

                    <!-- Botones de autenticación -->
                    <div class="flex items-center space-x-4">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="hidden md:inline-block dark:text-[#f5f5f0] border border-[#f5f5f0] hover:bg-[#c41e3a] hover:border-[#c41e3a] hover:text-[#f5f5f0] px-4 py-2 text-sm font-cinzel rounded transition-colors duration-200"
                        >
                            Dashboard
                        </Link>
                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="hidden md:inline-block dark:text-[#f5f5f0] hover:text-[#c41e3a] text-sm font-cinzel transition-colors duration-200"
                            >
                                Iniciar sesión
                            </Link>
                            <Link
                                :href="route('register')"
                                class="hidden md:inline-block dark:text-[#f5f5f0] border border-[#f5f5f0] hover:bg-[#c41e3a] hover:border-[#c41e3a] hover:text-[#f5f5f0] px-4 py-2 text-sm font-cinzel rounded transition-colors duration-200"
                            >
                                Register
                            </Link>
                        </template>
                        <Link
                            :href="route('carrito')"
                            class="ml-4 mr-4 pr-2 pl-2 md:ml-0 md:mr-0 md:mt-2 text-white dark:text-white-400 glitch"
                            id="cart"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="28" viewBox="0 0 256 256" class="w-6 h-6">
                                <path
                                    fill="currentColor"
                                    d="M225.21 56.31A12 12 0 0 0 216 52H58l-4.27-23.58A20 20 0 0 0 34.05 12H16a12 12 0 0 0 0 24h14.71l24.91 137a28 28 0 0 0 4.07 10.21A32 32 0 1 0 115 196h34a32 32 0 1 0 31-24H83.17a4 4 0 0 1-3.93-3.28L76.92 156H188.1a28 28 0 0 0 27.55-23l12.16-66.86a12 12 0 0 0-2.6-9.83ZM92 204a8 8 0 1 1-8-8a8 8 0 0 1 8 8Zm88 8a8 8 0 1 1 8-8a8 8 0 0 1-8 8Zm12-83.28a4 4 0 0 1-3.9 3.28H72.56L62.38 76h139.24Z"
                                />
                            </svg>
                            <span class="cart-item-number">{{ numJuegosSeleccionados }}</span>
                        </Link>

                        <!-- Botón de menú móvil -->
                        <button @click="isMenuOpen = !isMenuOpen" class="lg:hidden text-[#f5f5f0] hover:text-[#c41e3a] focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path
                                    v-if="!isMenuOpen"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"
                                />
                                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Menú móvil -->
            <div v-if="isMenuOpen" class="w-full absolute bg-black/80 lg:hidden py-4 border-t border-[#f5f5f0]/10 transition-all duration-300">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col space-y-2">
                        <template v-for="item in navigationItems" :key="item.name">
                            <Link
                                v-if="!item.subItems"
                                :href="item.href"
                                class="dark:text-[#f5f5f0] hover:text-[#c41e3a] px-4 py-2 text-sm font-cinzel font-medium transition-colors duration-200 border-b-2 border-transparent hover:border-[#c41e3a]"
                            >
                                {{ item.name }}
                            </Link>
                            <!-- Dropdown móvil -->
                            <div v-else>
                                <button
                                    @click="toggleMobileDropdown(item.name)"
                                    class="flex justify-between items-center w-full text-[#f5f5f0] hover:text-[#c41e3a] px-3 py-2 text-sm font-cinzel font-medium transition-colors duration-200"
                                >
                                    {{ item.name }}
                                    <ChevronDown
                                        class="h-4 w-4 duration-200"
                                        :class="{ 'transform rotate-180': openMobileDropdown === item.name }"
                                    ></ChevronDown>
                                </button>
                                <div v-if="openMobileDropdown === item.name" class="pl-4 py-2 space-y-2 bg-[#0a0a0a]/50">
                                    <template v-for="subItem in item.subItems" :key="subItem.name">
                                        <a
                                            :href="subItem.href"
                                            @click="isMenuOpen = false"
                                            class="block px-3 py-1 text-sm font-cinzel text-[#f5f5f0] hover:text-[#c41e3a]"
                                        >
                                            {{ subItem.name }}
                                        </a>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <!-- Botones de autenticación móvil -->
                        <div class="pt-4 mt-2 border-t border-[#f5f5f0]/10 flex space-x-4">
                            <Link
                                v-if="$page.props.auth.user"
                                :href="route('dashboard')"
                                @click="isMenuOpen = false"
                                class="text-[#f5f5f0] border border-[#f5f5f0] hover:bg-[#c41e3a] hover:border-[#c41e3a] hover:text-[#f5f5f0] px-4 py-2 text-sm font-cinzel rounded transition-colors duration-200"
                            >
                                Registrarse
                            </Link>
                            <template v-else>
                                <Link
                                    :href="route('login')"
                                    @click="isMenuOpen = false"
                                    class="text-[#f5f5f0] hover:text-[#c41e3a] px-3 py-2 text-sm font-cinzel transition-colors duration-200"
                                >
                                    Iniciar sesión
                                </Link>
                                <Link
                                    :href="route('register')"
                                    @click="isMenuOpen = false"
                                    class="text-[#f5f5f0] border border-[#f5f5f0] hover:bg-[#c41e3a] hover:border-[#c41e3a] hover:text-[#f5f5f0] px-4 py-2 text-sm font-cinzel rounded transition-colors duration-200"
                                >
                                    Registrarse
                                </Link>
                            </template>
                        </div>

                        <!-- Redes sociales móvil -->
                        <div class="pt-4 mt-2 border-t border-[#f5f5f0]/10">
                            <p class="text-[#f5f5f0]/70 text-xs mb-2 font-cinzel">SÍGUENOS:</p>
                            <div class="flex space-x-4">
                                <a
                                    href="https://www.facebook.com/gamerfest.ec"
                                    target="_blank"
                                    @click="isMenuOpen = false"
                                    class="text-[#f5f5f0] hover:text-[#c41e3a] transition-colors duration-200"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"
                                        />
                                    </svg>
                                </a>
                                <a
                                    href="https://www.instagram.com/gamerfest.ec"
                                    target="_blank"
                                    @click="isMenuOpen = false"
                                    class="text-[#f5f5f0] hover:text-[#c41e3a] transition-colors duration-200"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"
                                        />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    </div>
</template>
<style>
.cart-item-number {
    position: absolute;
    font-size: 10px;
    font-weight: bold;
    font-family: Arial, Helvetica, sans-serif;
    padding: 0.25em 0.35em;
    text-align: center;
    border-radius: 5px;
    background-color: #db005b;
    transform: translate(18px, -35px);
    min-width: 10px;
}
</style>
