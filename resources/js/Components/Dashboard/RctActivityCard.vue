<script setup>
import { computed, onMounted } from 'vue';

const props = defineProps({
    type: {
        type: String,
        default: 'Unknown'
    },
    activity: {
        type: String,
        default: 'Unknown'
    },
    activity_date: {
        type: String,
        default: 'Unknown'
    },
    tIcon: {
        type: Object,
        default: ''
    }
})

const typeIcon = {
    nConcern: `
        <svg xmlns="http://www.w3.org/2000/svg" height="19px" viewBox="0 -960 960 960" width="19px" fill="#0ec40bff">
            <path d="M480-360q17 0 28.5-11.5T520-400q0-17-11.5-28.5T480-440q-17 0-28.5 11.5T440-400q0 17 11.5 28.5T480-360Zm-40-160h80v-240h-80v240ZM80-80v-720q0-33 23.5-56.5T160-880h640q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H240L80-80Zm126-240h594v-480H160v525l46-45Zm-46 0v-480 480Z"/>
        </svg>
    `,
    nUser: `
        <i data-feather="users" class="w-4 h-4"></i>
    `,
    nComment: `
        <i data-feather="message-square" class="w-4 h-4"></i>
    `,
    nAgenda: `
        
    `,
    '':''
}

const iconColor = {
    nConcern: 'text-green-600 bg-green-50',
    nUser: 'text-blue-600 bg-blue-50',
    nComment: 'text-purple-600 bg-purple-50',
    nAgenda: 'text-teal-600 bg-teal-50',
}

const icon = computed(() => typeIcon[props.tIcon] )

onMounted(() => {
    document.getElementById('icon-type').innerHTML = icon.value
})
</script>

<template>
    <div class="flex items-start">
        <div class="p-2 rounded-lg mr-3" :class="iconColor[tIcon]">
            <div id="icon-type"></div>
            <slot name="optional-icon" />
        </div>
        <div>
            <p class="font-medium">{{ type }}</p>
            <p class="text-sm text-gray-500">{{ activity }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ activity_date }}</p>
        </div>
    </div>
</template>