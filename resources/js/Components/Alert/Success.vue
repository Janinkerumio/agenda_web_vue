<script setup>
import { ref, computed, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'

const props = defineProps({
    exitAction: Function
})

const page = usePage();

const successMessage = computed(() => page.props.flash?.success )
const showSuccess = ref(false)

watch(() => page.props.flash?.success,
    (success) => {
        showSuccess.value = success ? true : false
    },
    {
        immediate: true
    })

function closeSuccess() {
    showSuccess.value = false
    if(props.exitAction) {
        props.exitAction()
    }
}
</script>

<template>
    <div v-show="showSuccess" class="success-container flex flex-col bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative">
        {{ successMessage }}<span class="underline hover:text-green-500">
            <button @click="closeSuccess()" class="close-success text-green-500 border border-green-500 rounded-md px-2 py-1">Close</button>
        </span>
    </div>
</template>