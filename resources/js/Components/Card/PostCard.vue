<script setup>
defineProps({
    postTitle: String,
    primaryDate: String,
    primaryDateLabel: String,
    secondaryDate: String,
    info: String,
    infoLabel: String,
    status: String,
    statusColor: [String, Array],
    numbers: [String, Number, Array],
    numberLabel: String
})
</script>

<template>
    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
        <div class="bg-gray-50 border border-gray-200 rounded-lg flex items-center justify-center">
            <div class="p-3 min-w-full">
                <div class="flex flex-col w-full">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:gap-2">
                        <h2 class="text-xl font-bold text-gray-900">{{postTitle}}</h2>
                    </div>
                    <p v-if="secondaryDate" class="text-sm text-gray-500">{{secondaryDate}}</p>
                    <p v-if="primaryDate" class="text-gray-600"><span class="font-medium">{{ primaryDateLabel }}</span> {{primaryDate}}</p>
                    <div class="flex flex-col bg-white text-gray-500 text-md p-2 rounded-lg border border-gray-200 shadow mt-4">
                        <p class="flex items-center text-gray-500 text-xs mt-1 mb-2">{{ infoLabel }}</p>
                        {{ info }}
                        <span class="p-2 border-b-[0.25px] border-gray-300 w-full"></span>
                        <div v-if="status || numberLabel || numbers" class="flex items-center gap-2 mt-2 mb-2">
                            <span v-if="status" class="px-2 py-1 text-sm rounded-lg" :class="statusColor">{{status}}</span>
                            <span v-if="numberLabel || numbers" class="px-2 py-1 text-sm text-gray-600 rounded-lg">{{ numberLabel }}<span class="rounded-full bg-red-500 text-white px-2 py-1">{{numbers}}</span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex items-center mt-3 justify-between">
            <div></div>
            <slot name="actions" />
        </div>
    </div>
</template>