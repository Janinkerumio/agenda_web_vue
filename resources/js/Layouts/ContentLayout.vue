<script setup>
defineProps({
    content_title: {
        type: String,
        default: ''
    }
})
</script>

<template>
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">
            {{ content_title }}
        </h2>
        <div class="flex flex-col items-center gap-2">
            <slot name="content-head-buttons" />
        </div>
    </div>
    <slot name="main-content" />
</template>