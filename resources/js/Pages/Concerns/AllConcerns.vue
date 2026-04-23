<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ContentLayout from '@/Layouts/ContentLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed, onMounted } from 'vue';
import { timeAgo, dateToString } from '@/utils/date';
import { statusColors } from '@/utils/status';
import PostCard from '@/Components/Card/PostCard.vue';
import AllowedUserActions from '@/Components/Button/AllowedUserActions.vue';
import GuestUserActions from '@/Components/Button/GuestUserActions.vue';

defineProps({
    concerns: Object
})

const page = usePage()
const user = computed(() => page.props.auth?.user )

const view = (concernId) => {
    console.log('Concern ID: ' +concernId)
}
const edit = (concernId) => {
    console.log('Concern ID: ' +concernId)
}
const deleteAct = (concernId) => {
    console.log('Concern ID: ' +concernId)
}

</script>

<template>
    <Head title="Concerns" />

    <AppLayout>
        <ContentLayout content_title="Concerns">
             <template #content-head-buttons>

            </template>
            <template #main-content>
                <div id="concern-container" class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                    <div v-if="concerns.data.length === 0" class="flex items-center justify-center p-6">
                        <h3 class="text-lg text-gray-600">No concerns yet</h3>
                    </div>
                    <PostCard v-for="(concern, index) in concerns.data" :key="index"
                        :post-title="concern.responsible?.name ?? 'Unknown'"
                        :primary-date="concern.due_date ? dateToString('longDate', concern.due_date) : 'N/A'"
                        primary-date-label="Due Date:"
                        :secondary-date="timeAgo(concern.updated_at)"
                        :info="concern.description ?? 'N/A'"
                        info-label="Description:"
                        :status="concern.status"
                        :status-color="statusColors[concern.status]"
                        :numbers="concern.comment_list_count >= 100 ? '99+' : concern.comment_list_count"
                        number-label="Comments:"
                    >
                        <template #actions>
                            <AllowedUserActions v-if="user?.role === 'admin'" 
                                :handle-view="() => view(concern.concern_id)" 
                                :handle-edit="() => edit(concern.concern_id)" 
                                :handle-delete="() => deleteAct(concern.concern_id)"
                            />
                            <GuestUserActions v-else :handle-view="() => view(concern.concern_id)" />
                        </template>
                    </PostCard>
                </div>
            </template>
        </ContentLayout>
    </AppLayout>
</template>