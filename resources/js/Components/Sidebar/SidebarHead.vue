<script setup>
import { ref, onMounted, onUnmounted, nextTick } from 'vue';

const props = defineProps({
    companyLogoUrl: {
        type: String,
        default: null
    },
    companyName: {
        type: String,
        default: 'Company Name'
    }
});

const isCollapsed = ref(false);
const isAccordionOpen = ref(null);
const isMobile = ref(false);

let mediaQuery

const toggleSidebar = () => {
    isCollapsed.value = !isCollapsed.value;
    window.toggleSidebar(isCollapsed.value);

    isAccordionOpen.value?.toggle();
};

const openSidebar = () => {
    isCollapsed.value = false;
    window.toggleSidebar(false);
};

onMounted(() => {
    mediaQuery = window.matchMedia('(max-width: 768px)')
    isMobile.value = mediaQuery.matches

    const handler = (e) => {
        isMobile.value = e.matches
    }

    mediaQuery.addEventListener('change', handler)
    onUnmounted(() => {
        mediaQuery.removeEventListener('change', handler)
    })
});

defineExpose({ isCollapsed, toggleSidebar, openSidebar });
</script>

<template>
    <div class="p-4 flex justify-between items-center">
        <button id="open-sidebar" @click="openSidebar" class="text-gray-500 hover:text-gray-700 py-3 ml-2 hidden">
            <i data-feather="chevron-right"></i>
        </button>
        <div id="side-logo" class="flex flex-col space-x-2">
            <img v-if="companyLogoUrl" :src="companyLogoUrl" alt="Company Logo" class="w-8 h-8 object-contain" />
            <i v-else class="fa-regular fa-building px-10 text-blue-800 text-2xl"></i>
            <div>
                <h1 class="text-md font-bold text-blue-800 whitespace-nowrap">{{ companyName }}</h1>
            </div>
        </div>
        <button id="toggle-sidebar" @click="toggleSidebar" class="text-gray-500 hover:text-gray-700" :class="[ isMobile ? 'hidden' : '']">
            <i data-feather="chevron-left"></i>
        </button>
        <button id="mobile-toggle-sidebar" class="text-gray-500 hover:text-gray-700 py-3 ml-2 md:hidden">
            <i data-feather="chevron-left"></i>
        </button>
    </div>
</template>