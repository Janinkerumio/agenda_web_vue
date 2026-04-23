<script setup>
import HeadButton from '@/Components/Button/HeadButton.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import ContentLayout from '@/Layouts/ContentLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { computed, onMounted } from 'vue';
import { dateToString } from '@/utils/date';
import { statusColors } from '@/utils/status';
import PostCard from '@/Components/Card/PostCard.vue';
import AllowedUserActions from '@/Components/Button/AllowedUserActions.vue';
import GuestUserActions from '@/Components/Button/GuestUserActions.vue';

defineProps({
    agendas: Object
})

const page = usePage()
const user = computed(() => page.props.auth?.user )

const view = (agendaId) => {
    router.get(route('agenda.view', agendaId))
}
const edit = (agendaId) => {
    console.log('Edit agenda with ID: '+ agendaId)
}
const deleteAct = (agendaId) => {
    console.log('Delete agenda with ID: '+agendaId)
}

</script>

<template>
    <Head title="Agendas" />
    <AppLayout>
        <ContentLayout content_title="Agendas">
            <template #content-head-buttons>
                <HeadButton v-if="user?.role === 'admin'" @click="router.get(route('agenda.create'))" :class="['bg-blue-600']">
                    <i data-feather="plus" class="mr-2 text-xs"></i><span>New Agenda</span>
                </HeadButton>
            </template>
            <template #main-content>
                <div id="agenda-container" class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                    <div v-if="agendas.data.length === 0" class="flex items-center justify-center p-6">
                        <h3 class="text-lg text-gray-600">No agendas yet</h3>
                    </div>
                    <PostCard v-for="(agenda, index) in agendas.data" :key="index"
                        :post-title="agenda.title"
                        :primary-date="dateToString('longDate', agenda.date)"
                        primary-date-label="Due Date:"
                        :info="agenda.notes ? agenda.notes : 'No notes yet'"
                        info-label="Notes:"
                        :status="agenda.status"
                        :status-color="statusColors[agenda.status]"
                        :numbers="agenda.concerns_count"
                        number-label="Concerns:"
                    >
                        <template #actions>
                            <AllowedUserActions v-if="user?.role === 'admin'" 
                                :handle-view="() => view(agenda.agenda_id)" 
                                :handle-edit="() => edit(agenda.agenda_id)" 
                                :handle-delete="() => deleteAct(agenda.agenda_id)"
                            />
                            <GuestUserActions v-else :handle-view="() => view(agenda.agenda_id)" />
                        </template>
                    </PostCard>
                </div>
            </template>
        </ContentLayout>
    </AppLayout>
</template>