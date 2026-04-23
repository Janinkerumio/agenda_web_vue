<script setup>
const model = defineModel()
</script>

<template>
    <textarea 
        v-model="model"
        rows="4"
        class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm 
               focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 
               transition-all duration-300"
    ></textarea>
</template>