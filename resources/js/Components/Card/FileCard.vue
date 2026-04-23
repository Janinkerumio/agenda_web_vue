<script setup>
import { computed } from 'vue';

const props = defineProps({
    fileAttachment: String
})

const extension = computed(() => {
    return props.fileAttachment.split('.').pop().toLowerCase()
})
</script>

<template>
    <div class="bg-white rounded-2xl shadow p-6">
        <h2 class="text-xl font-semibold mb-3 text-gray-800">File Attachment</h2>
        <div class="flex flex-col gap-4 border-t border-gray-200 pt-3">
            <p class="text-gray-700 break-all w-64">{{ fileAttachment.split('/').pop() }}</p>
            <!-- Image preview -->
            <img v-if="['jpg', 'jpeg', 'png', 'gif'].includes(extension)"
                :src="`/storage/${fileAttachment}`"
                alt="Preview" 
                class="w-64 h-64 rounded-lg shadow"
            >
            <!-- PDF preview -->
            <iframe v-if="extension === 'pdf'"
                :src="`/storage/${fileAttachment}`" 
                class="w-full h-64 border rounded-lg">
            </iframe>
            <a :href="`/storage/${fileAttachment}`"
                target="_blank"
                class="text-blue-600 hover:text-blue-800 font-medium">
                View / Download
            </a>
        </div>
    </div>
</template>