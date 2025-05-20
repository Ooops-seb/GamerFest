<script setup lang="ts">
import { ExternalLink } from 'lucide-vue-next';
import logoLight from '@/../../public/images/Logo2025black.webp';
import logoDark from '@/../../public/images/Logo2025white.webp';
</script>

<template>
    <footer id="contact" class="dark:bg-black border-t border-gray/10 dark:border-beige/10 pt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div class="md:col-span-1">
                    <div class="flex items-center mb-4">
                        <picture class="select-none pointer-events-none">
                            <source :srcset="logoLight" media="(prefers-color-scheme: dark)" />
                            <img class="w-32 mx-auto" :src="logoDark" alt="Logo" />
                        </picture>
                    </div>
                    <p class="dark:text-beige/70 text-sm mb-4">El evento gaming más importante del año. Torneos, exhibiciones y mucho más.</p>
                    <p class="dark:text-beige text-sm font-cinzel">JUNIO 3, 4 y 5</p>
                </div>

                <div class="md:col-span-1">
                    <h3 class="dark:text-beige text-sm font-cinzel font-bold mb-4 tracking-wider">ENLACES</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="#nav" class="dark:text-beige/70 hover:text-wine text-sm transition-colors duration-200"> Inicio </a>
                        </li>
                        <li>
                            <a href="#sponsors" class="dark:text-beige/70 hover:text-wine text-sm transition-colors duration-200"> Sponsors </a>
                        </li>
                        <li>
                            <a href="#contact" class="dark:text-beige/70 hover:text-wine text-sm transition-colors duration-200"> Contacto </a>
                        </li>
                    </ul>
                </div>

                <div class="md:col-span-1">
                    <h3 class="dark:text-beige text-sm font-cinzel font-bold mb-4 tracking-wider">LEGAL</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="#" class="dark:text-beige/70 hover:text-wine text-sm transition-colors duration-200"> Términos y condiciones </a>
                        </li>
                    </ul>
                </div>

                <div class="md:col-span-1">
                    <h3 class="dark:text-beige text-sm font-cinzel font-bold mb-4 tracking-wider">SÍGUENOS</h3>
                    <div class="flex space-x-4 mb-4">
                        <a
                            href="https://www.facebook.com/gamerfest.ec"
                            target="_blank"
                            class="dark:text-beige hover:text-wine transition-colors duration-200"
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
                            class="dark:text-beige hover:text-wine transition-colors duration-200"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"
                                />
                            </svg>
                        </a>
                    </div>
                    <h3 class="dark:text-beige text-sm font-cinzel font-bold mb-2 tracking-wider">CONTACTO</h3>
                    <p class="dark:text-beige/70 text-sm">info@gamerfestespel.com</p>
                </div>
            </div>

            <div class="border-t border-gray/10 dark:border-beige/10 my-6"></div>

            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="dark:text-beige/50 text-xs">&copy; 2025 GamerFest - ESPEL. Todos los derechos reservados.</p>
                <div class="flex space-x-4 mt-4 md:mt-0">
                    <a
                        href="https://espe-el.espe.edu.ec/"
                        target="_blank"
                        class="dark:text-beige/50 hover:text-wine text-xs transition-colors duration-200 flex items-center"
                    >
                        Universidad de las Fuerzas Armadas - ESPE Sede Latacunga
                        <ExternalLink class="ml-1 w-3"></ExternalLink>
                    </a>
                </div>
            </div>
        </div>
    </footer>
</template>
