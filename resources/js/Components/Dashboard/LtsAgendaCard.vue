<script setup>
defineProps({
    agenda_title: {
        type: String,
        default: '',
    },
    date: {
        type: String,
        default: 'Mmm. dd, yyyy'
    },
    notes: {
        type: String,
        default: 'N/A'
    }
})
</script>

<template>
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 lg:col-span-2">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-semibold text-gray-800">Latest Agenda</h3>
        </div>
        <div class="h-64 max-h-96 bg-gray-50 rounded-lg flex items-center justify-center">
            <div class="p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">{{ agenda_title }}</h2>
                        <p class="mt-2 mb-2"><span class="px-4 py-2 text-sm bg-green-100 text-green-700 rounded-lg">Upcoming</span></p>
                        <p class="text-gray-600"><span class="font-medium">Date:</span> {{ date }}</p>
                        <!-- <p class="text-gray-600"><span class="font-medium">Attendees:</span> Jane Smith, Alice Brown, Charlie Wilson</p> -->
                        <p class="flex items-center text-gray-500 text-xs mt-3">
                            <span class="mr-2 border-b-[0.25px] border-gray-500 mt-1 w-10"></span>
                                Notes
                            <span class="ml-2 border-b-[0.25px] border-gray-500 mt-1 w-full"></span>
                        </p>
                        <p class="text-sm text-gray-500 mt-2">{{ notes }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>