<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ContentLayout from '@/Layouts/ContentLayout.vue';
import Months from '@/Components/Calendar/Months.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title=" Calendar"/>

    <AppLayout>
        <ContentLayout content_title="Calendar">
            <template #content-head-buttons>

            </template>

            <template #main-content>
                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-3 lg:col-span-2 p-4 border-2 rounded-xl border-gray-200 shadow-md">
                        <Months />
                    </div>
                    <div class="border-2 border-gray-200 p-4 rounded-xl shadow-sm">
                        <h2 class="text-xl font-bold text-gray-700 p-4">Today</h2>
                    </div>
                </div>
            </template>
        </ContentLayout>
    </AppLayout>
</template>