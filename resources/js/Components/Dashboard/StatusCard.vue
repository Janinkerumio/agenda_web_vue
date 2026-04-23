<script setup>
defineProps({
    title: {
        type: String,
        default: ''
    },
    val: {
        type: Number,
        default: 0
    }
})
</script>

<template>
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-500">{{ title }}</p>
                <h3 class="text-2xl font-bold mt-2">{{ val }}</h3>
                <p class="text-gray-500 text-sm mt-1 flex items-center">
                    <span class="text-sm font-medium px-1">
                        <slot name="details"/>
                    </span>
                </p>
            </div>
            <div class="p-3 rounded-lg bg-green-50 text-green-600">
                <slot name="icon"/>
            </div>
        </div>
    </div>
</template>