<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import logoLight from '@/../../public/images/Logo2025black.webp';
import logoDark from '@/../../public/images/Logo2025white.webp';
</script>

<template>
    <Head title="Bienvenidos"> </Head>
    <div
        class="flex min-h-screen flex-col items-center justify-center bg-beige bg-[url('/public/images/texture.webp')] dark:bg-none p-6 text-[#1b1b18] lg:p-8 dark:bg-[#070706]"
    >
        <header class="not-has-[nav]:hidden mb-6 w-full max-w-[335px] text-sm lg:max-w-4xl">
            <nav class="flex items-center justify-end gap-4">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                >
                    Dashboard
                </Link>
                <template v-else>
                    <Link
                        :href="route('login')"
                        class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                    >
                        Log in
                    </Link>
                    <Link
                        :href="route('register')"
                        class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                    >
                        Register
                    </Link>
                </template>
            </nav>
        </header>
        <div class="flex flex-col items-center justify-center opacity-100 transition-opacity duration-750 starting:opacity-0">
            <picture>
                <source :srcset="logoLight" media="(prefers-color-scheme: dark)" />
                <img class="w-98 mx-auto" :src="logoDark" alt="Logo" />
            </picture>
            <span class="font-cinzel text-wine dark:text-beige text-2xl">Próximamente</span>
            <span class="font-cinzel text-wine dark:text-beige text-base">Junio 10, 11, 12</span>
        </div>
    </div>
</template>
