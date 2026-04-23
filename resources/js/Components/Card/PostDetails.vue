<script setup>
defineProps({
    details: {
        type: String,
        required: true
    },
    detailsTitle: {
        type: String,
        required: true
    },
    status: String,
    statusColor: String
})
</script>
<template>
    <div class="sm:col-span-2 bg-white rounded-2xl shadow p-6 min-h-80 max-h-4xl">
        <h2 class="text-xl font-semibold mb-3 text-gray-800">{{ detailsTitle }}</h2>
        <div class="border-t border-gray-200 mt-2 pt-3 text-gray-700">
            <p class="mt-2 mb-2"><strong>Status:</strong>
                <span class="px-2 py-1 rounded text-sm ml-4" :class="statusColor">
                    {{ status ? status : 'Unknown' }}
                </span>
            </p>
            <div class="flex flex-col gap-4 bg-gray-50 p-3 rounded-lg border border-gray-200 mt-8">
                <div class="flex items-center text-gray-500 text-sm">
                    <span class="mr-2 border-b-[0.25px] border-gray-300 mt-1 w-10"></span>
                        Notes
                    <span class="ml-2 border-b-[0.25px] border-gray-300 mt-1 w-full"></span>
                </div>
                <span class="text-base text-gray-700">{{ details }}</span>
            </div>
            <div class="flex items-center p-3 justify-between mt-3">
                <div></div>
                <div class="text-base font-medium rounded-lg border border-gray-400">
                    <slot name="actions" />
                </div>
            </div>
        </div>
    </div>
</template>