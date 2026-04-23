<script setup>
import { ref, computed, watch, onMounted, nextTick, onUnmounted } from 'vue'

const props = defineProps({
    label: { type: String },
    icon: { type: String },
    routePrefix: { type: String },
})

const isOpen = ref(false)
const isSidebarCollapsed = ref(false)
const arrowIcon = ref(null)

const isActive = computed(() => {
    try {
        return route().current(`${props.routePrefix}.*`)
    } catch {
        return false
    }
})

watch(isActive, (newVal) => {
    if (newVal) {
        isOpen.value = true
    }
}, { immediate: true })

const rotateArrow = () => {
    if (arrowIcon.value) {
        const svg = arrowIcon.value.querySelector('svg');
        if (svg) {
            const shouldRotate = isOpen.value && !isSidebarCollapsed.value;
            svg.style.transform = shouldRotate ? 'rotate(180deg)' : 'rotate(0deg)';
            svg.style.transition = 'transform 0.3s ease-in-out';
        }
    }
};

watch([isOpen, isSidebarCollapsed], () => {
    nextTick(rotateArrow);
});

const checkSidebarState = () => {
    const sidebar = document.getElementById('sidebar');
    if (sidebar) {
        isSidebarCollapsed.value = sidebar.classList.contains('w-20');
    }
};

const toggle = () => {
    const sidebar = document.getElementById('sidebar');
    const isCollapsed = sidebar?.classList.contains('w-20') || false;
    
    if (isCollapsed) {
        window.toggleSidebar(false);
    } else {
        isOpen.value = !isOpen.value;
    }
    
    if (window.toggleSidebarOnNavClick) {
        window.toggleSidebarOnNavClick();
    }
}

const initIcon = () => {
    nextTick(() => {
        checkSidebarState();
        rotateArrow();
    });
};

onMounted(() => {
    initIcon();
    window.addEventListener('resize', checkSidebarState);
    window.addEventListener('sidebar-toggle', checkSidebarState);
    setTimeout(checkSidebarState, 100);
});

onUnmounted(() => {
    window.removeEventListener('resize', checkSidebarState);
    window.removeEventListener('sidebar-toggle', checkSidebarState);
});
</script>

<template>
    <div class="accordion-group">
        <button
            @click="toggle"
            :class="[
                'w-full flex items-center justify-between p-2 rounded-lg group relative transition-all duration-300 ease-in-out',
                isActive ? 'bg-blue-100 font-medium text-blue-500' : 'text-gray-700 hover:bg-gray-100'
            ]">
            <div class="flex items-center space-x-3">
                <i :data-feather="icon"></i>
                <span class="sidebar-text"> {{ label }}</span>
                <span class="sidebar-tooltip hidden absolute left-full ml-2 px-2 py-1 bg-gray-800 text-white text-xs rounded whitespace-nowrap">{{ label }}</span>
            </div>
            <span ref="arrowIcon" class="inline-block" :class="{ 'hidden': isSidebarCollapsed }">
                <i data-feather="chevron-down"></i>
            </span>
        </button>
        <div v-show="isOpen && !isSidebarCollapsed" class="pl-8 mt-1 space-y-1 transition-all duration-300 ease-in-out">
            <slot>

            </slot>
        </div>
    </div>
</template>