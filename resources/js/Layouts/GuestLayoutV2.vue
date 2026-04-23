<script setup>
import { ref, onMounted } from 'vue';
</script>

<template>
    <div class="grid grid-cols-1 sm:grid-cols-2 min-h-screen">
        <div class="flex flex-col items-center justify-center px-4 sm:px-0 min-h-screen bg-gradient-to-r from-blue-600 to-amber-600">
            <div class="w-full sm:w-[50%] p-4 rounded-2xl shadow-md bg-white/20 dark:bg-gray-800/80 backdrop-blur-lg">
                <slot />
            </div>
        </div>
        <div class="sm:flex bg-gray-100 dark:bg-gray-800 hidden min-h-screen items-center justify-center">
            <div class="flex flex-col items-center gap-8">
                <div class="relative">
                    <div class="absolute inset-0 bg-blue-500 blur-3xl opacity-30 rounded-full"></div>
                    <div class="relative flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 text-amber-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zM14.25 15h.008v.008H14.25V15zm0 2.25h.008v.008H14.25v-.008zM17.25 15h.008v.008H17.25V15zm0 2.25h.008v.008H17.25v-.008zM12 18.75h.008v.008H12v-.008z" />
                        </svg>
                        <div class="flex items-center gap-3 font-mono tracking-widest text-2xl sm:text-4xl font-semibold">
                            <span class="text-gray-400 dark:text-white mr-5">A G E N D A</span>
                            <span class="text-blue-400 tracking-tight">W E B</span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col items-center gap-2 text-center">
                    <p class="text-gray-600 dark:text-white/90 text-lg font-light">Plan. Organize. Achieve.</p>
                    <p class="text-gray-500 dark:text-white/60 text-sm max-w-xs">Your all-in-one calendar and event management solution</p>
                </div>
            </div>
        </div>
    </div>
</template>