<script setup>
import HeadButton from '@/Components/Button/HeadButton.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import ContentLayout from '@/Layouts/ContentLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { dateToString,  } from '@/utils/date';
import { statusColors, statusLabels } from '@/utils/status';
import { computed } from 'vue';
import FileCard from '@/Components/Card/FileCard.vue';
import PostDetailsHeader from '@/Components/Header/PostDetailsHeader.vue';
import PostDetails from '@/Components/Card/PostDetails.vue';

const props = defineProps({
    agenda: Object,
    attachment: Object,
    creator: Object,
})

const page = usePage();
const user = computed(() => page.props.auth?.user )

</script>

<template>
    <Head title="View Agenda" />

    <AppLayout>
        <ContentLayout content_title="View Agenda">
            <template #content-head-buttons>
                <HeadButton @click="router.get(route('agenda.view-all'))" :class="['bg-amber-500']">
                    <i class="fa-solid fa-arrow-left"></i><span>Back to List</span>
                </HeadButton>
            </template>
            <template #main-content>
                <div class="flex-1 overflow-y-auto">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 p-2 max-w-full mx-auto">
                        <PostDetailsHeader
                            :title="agenda.title"
                            :date="dateToString('longDate', agenda.date)"
                            :author="creator"
                        />
                        <PostDetails
                            :details="agenda.notes"
                            details-title="Agenda Details"
                            :status="agenda.status ?? statusLabels[agenda.status]"
                            :status-color="statusColors[agenda.status]"
                        >
                            <template #actions v-if="user === agenda.created_by || user.role === 'admin'">
                                <button type="button" @click="router.get(route('agenda.edit-prev', agenda.agenda_id))" class="border-r text-slate-500 border-gray-400 px-3 py-2 rounded-l-lg hover:text-slate-400">Edit</button>
                                <button id="delete-agenda-btn" class="px-3 text-red-600 py-2 rounded-r-lg hover:text-red-500">
                                    Delete
                                </button>
                            </template>
                        </PostDetails>
                        <FileCard v-if="attachment" :file-attachment="attachment" />
                        <div v-else></div>
                        <div class="sm:col-span-3 bg-white rounded-2xl p-3 border border-gray-200 shadow-md">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between">
                                <h2 class="p-2 mt-2 ml-2 text-xl font-semibold">Concerns</h2>
                                <button v-if="['admin', 'member'].includes(user?.role)" 
                                    type="button"
                                    @click="router.get(route('concerns.create-preview', agenda.agenda_id))"
                                    class="flex items-center gap-2 text-sm bg-amber-500 text-white px-4 py-2 rounded-lg hover:bg-amber-600 transition ml-3"
                                >
                                    <i class="fa-solid fa-plus text-xs"></i><span>Add Concern</span>
                                </button>
                            </div>
                            <div class="px-5 border-b border-gray-300 mb-3 mt-3 w-full"></div>
                            <div id="concerns-container"></div>
                            <div class="mt-5 text-xxs sm:text-sm px-4">
                                <nav id="pagination" aria-label="Pagination Navigation" class="inline-flex items-center space-x-2 text-sm font-semibold"></nav>
                                <div id="pagination-meta" class="mt-2"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </ContentLayout>
    </AppLayout>
</template>