<script setup>
defineProps({
    formTitle: String
})
</script>

<template>
    <div class="max-w-5xl mx-auto bg-white shadow-md rounded-2xl p-6 border border-gray-200">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 border-b pb-2">{{ formTitle }}</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <slot name="fields" />
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2">
            <div></div>
            <div class="mt-6 flex justify-end gap-5">
                <slot name="buttons" />
            </div>
        </div>
    </div>
</template>