<script setup>
defineProps({
    initialValLabel: [Number, String],
    initialValue: [Number, String],
})

const model = defineModel()
</script>
<template>
    <select v-model="model" class="w-full border border-gray-300 rounded-md p-2">
        <option v-if="initialValLabel || initialValue" 
            :value="initialValue" 
        >
            {{ initialValLabel ?? '-- Select --'}}
        </option>
        <slot />
    </select>
</template>