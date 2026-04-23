<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ContentLayout from '@/Layouts/ContentLayout.vue';
import Unauthorized from '@/Components/Placeholder/Unauthorized.vue';
import { Head, usePage, router } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import { timeAgo } from '@/utils/date';

const page = usePage()
const user = computed(() => page.props.auth?.user )
const memberReqCount = ref(0);

const viewMemberRequest = (userId) => {
    console.log('Member: '+ userId)
}

onMounted(() => {
    page.props.data.forEach(() => {
        memberReqCount.value++
    });
    console.log(page.props.data);
})
</script>

<template>
    <Head title="Memberships" />

    <AppLayout>
        <ContentLayout v-if="user?.role === 'admin'" content_title="Memberships">
            <template #content-head-buttons>
                <button @click="router.get(route('people'))" class="flex items-center justify-between gap-3 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-opacity-90 transition">
                    <i class="fa-solid fa-arrow-left"></i><p>Back</p>
                </button>
            </template>

            <template #main-content>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 overflow-y-auto p-2">
                    <div class="sm:col-span-2 col-span-3">
                        <div class="flex flex-col border border-gray-400 rounded-lg shadow-md bg-white mb-2">
                            <div class="p-3 flex justify-between">
                                <h2 class="text-xl font-semibold">Membership requests <span id="request-count" class="text-blue-500">{{ memberReqCount }}</span></h2>
                            </div>
                            <div class="px-8 py-2 mb-2 max-h-80 overflow-auto">
                                <div v-if="page.props.data.length > 0" class="flex flex-col gap-2">
                                    <div v-for="(member) in page.props.data" :key="page.props.data.id" class="flex flex-col rounded-xl bg-gray-100 px-4 py-3">
                                        <p class="text-lg text-gray-800 font-medium">{{ member.name}}</p>
                                        <p class="ml-4 text-base text-gray-600">{{ timeAgo(member.created_at) }}</p>
                                        <div class="flex flex-row gap-2 mt-3">
                                            <button @click="viewMemberRequest(member.id)" class="view-req-btn rounded-lg bg-blue-500 text-white font-medium text-sm py-2 px-8 hover:bg-blue-400 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300">View</button>
                                            <button class="rounded-lg bg-gray-500 text-white font-medium text-sm py-2 px-8 hover:bg-gray-400 focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-300">Delete</button>
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="flex flex-col rounded-xl bg-gray-100 px-4 py-3">
                                    <p class="text-lg text-gray-800 font-medium">No requests yet</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </ContentLayout>
        <Unauthorized v-else />
    </AppLayout>
</template>