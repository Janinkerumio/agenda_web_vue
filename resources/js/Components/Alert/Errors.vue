<script setup>
import { ref, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage();

const errors = ref(null)
const showErrors = ref(null)

watch(() => page.props.errors,
    (newErrors) => {
        errors.value = newErrors || {}
        showErrors.value = Object.keys(errors.value).length > 0
    },
    { 
        immediate: true 
    }
)

function closeErrors() {
    showErrors.value = false
}
</script>

<template>
    <div v-show="showErrors" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative">
        <ul class="list-disc pl-5">
            <li v-for="(error, key) in errors" :key=key>{{ error }}</li>
        </ul>
        <button @click="closeErrors" class="text-red-600 rounded-md border border-red-500 px-3 py-1">Close</button> 
    </div>
</template>