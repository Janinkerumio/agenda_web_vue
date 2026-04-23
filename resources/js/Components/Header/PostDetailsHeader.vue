<script setup>
defineProps({
    title: {
        type: String,
        required: true
    },
    date: String,
    author: String
})
</script>
<template>
    <div class="sm:col-span-3 p-4">
        <div class="flex items-center justify-between">
            <div class="flex gap-4 flex-col">
                <h1 class="text-3xl font-bold text-gray-900">{{ title }}</h1>
                <div class="text-lg flex flex-col gap-2 mt-1">
                    <p class="text-gray-600">Date: <span class="font-medium">{{ date ?? 'Unknown' }}</span></p>
                    <p class="text-gray-600">Created by: <span class="font-medium">{{ author ?? 'N/A' }}</span></p>
                </div>
            </div>
        </div>
    </div>
</template>