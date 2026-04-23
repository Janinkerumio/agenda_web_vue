<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ContentLayout from '@/Layouts/ContentLayout.vue';
import StatusCard from '@/Components/Dashboard/StatusCard.vue';
import LtsAgendaCard from '@/Components/Dashboard/LtsAgendaCard.vue';
import RctActivityCard from '@/Components/Dashboard/RctActivityCard.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed, onMounted } from 'vue';
import { dashboardData } from '@/config/data';
import { statusLabels, concernStatusColors } from '@/utils/status';
import HeadButton from '@/Components/Button/HeadButton.vue';

const page = usePage();
const user = computed(() => page.props.auth?.user)

</script>

<template>
    <Head title="Dashboard" />

    <AppLayout>
        <ContentLayout content_title="Dashboard">
            <template #content-head-buttons>
                <HeadButton v-if="user.role === 'admin'" @click="router.get('#')" :class="['bg-blue-600']">
                    <i data-feather="plus" class="mr-2"></i><span>New Report</span>
                </HeadButton>
            </template>

            <template #main-content>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <StatusCard title="Upcoming Agenda" :val="dashboardData.statusValues.upcAgenda">
                        <template #icon>
                            <svg class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </template>
                    </StatusCard>

                    <StatusCard title="Open Concerns" :val="dashboardData.statusValues.openConcerns">
                        <template #icon>
                            <svg class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </template>
                    </StatusCard>

                    <StatusCard title="Closed Concerns" :val="dashboardData.statusValues.closedConcerns">
                        <template #icon>
                            <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </template>
                    </StatusCard>

                    <StatusCard title="% Completion" :val="`${dashboardData.statusValues.completionPerc} %`">
                        <template #icon>
                            <svg class="h-6 w-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </template>
                        <template #details>
                            Out of <span class="text-indigo-700 text-sm font-medium px-1"> {{ dashboardData.statusValues.totalEntries }} </span> entries
                        </template>
                    </StatusCard>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <LtsAgendaCard 
                        :agenda_title="dashboardData.latestAgenda.agenda_title" 
                        :date="dashboardData.latestAgenda.date"
                        :notes="dashboardData.latestAgenda.notes"
                    />

                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <h3 class="font-semibold text-gray-800 mb-6">Recent Activity</h3>
                        <div class="space-y-4">
                            <RctActivityCard v-for="(rctAct, index) in dashboardData.recentActivites" :key="index"
                                :type="rctAct.type"
                                :activity="rctAct.activity"
                                :activity_date="rctAct.activity_date"
                                :tIcon="rctAct.tIcon"
                            />
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-semibold text-gray-800">Recent Concerns</h3>
                        <Link href="#" class="text-primary text-sm font-medium">View all</Link>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="text-left text-gray-500 text-sm border-b border-gray-200">
                                    <th class="pb-3">Person</th>
                                    <th class="pb-3">Status</th>
                                    <th class="pb-3">Date raised</th>
                                    <th class="pb-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr v-for="(recentConcern, index) in dashboardData.recentConcerns" :key="index">
                                    <td class="py-4">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 flex items-center justify-center mr-3">
                                                <img :src="recentConcern.imageUrl" class="w-8 h-8 rounded-full border-2 border-white" alt="Team member">
                                            </div>
                                            <div>
                                                <p class="font-medium">{{ recentConcern.name }}</p>
                                                <p class="text-sm text-gray-500">{{ recentConcern.concernType }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="px-2 py-1 text-xs rounded-full" :class="concernStatusColors[recentConcern.status]">{{ statusLabels[recentConcern.status] }}</span>
                                    </td>
                                    <td class="text-sm text-gray-500">{{ recentConcern.dateRaised }}</td>
                                    <td>
                                        <button class="text-gray-400 hover:text-gray-600">
                                            <i data-feather="more-vertical"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </template>
        </ContentLayout>
    </AppLayout>
</template>
