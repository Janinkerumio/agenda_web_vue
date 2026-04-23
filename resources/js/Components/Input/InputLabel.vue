<script setup>
defineProps({
    labelFor: { 
        type: String,
    }
})
</script>

<template>
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">
        <span v-if="labelFor">{{ labelFor }}</span>
        <span v-else><slot /></span>
    </label>
</template>