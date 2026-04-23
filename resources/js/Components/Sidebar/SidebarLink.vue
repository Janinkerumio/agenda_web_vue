<script setup>
import { Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    href: { type: String },
    routeName: { type: String },
    label: { type: String },
});

const isActive = computed(() => route().current(props.routeName));

const handleClick = () => {
    if (window.toggleSidebarOnNavClick) {
        window.toggleSidebarOnNavClick();
    }
    if (window.toggleSidebar) {
        window.toggleSidebar(false);
    }
};
</script>

<template>
    <Link :href="href" @click="handleClick"
        :class="[
            'flex items-center space-x-3 px-2 py-2 rounded-lg',
            isActive ? 'bg-blue-100 font-medium text-blue-500' : 'text-gray-700 hover:bg-gray-100',
            'group transition-all duration-300 ease-in-out',
        ]">
        <slot>

        </slot>
        <span class="sidebar-text">{{ label }}</span>
        <span class="sidebar-tooltip hidden absolute left-full ml-2 px-2 py-1 bg-gray-800 text-white text-xs rounded whitespace-nowrap"> {{ label }}</span>
    </Link>
</template>