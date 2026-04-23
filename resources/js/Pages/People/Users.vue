<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ContentLayout from '@/Layouts/ContentLayout.vue';
import Unauthorized from '@/Components/Placeholder/Unauthorized.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';

const page = usePage()
const user = computed(() => page.props.auth?.user )
const memberReqCount = ref(0);

onMounted(() => {

    if(page.props?.data) {
        page.props.data.forEach(element => {
            memberReqCount.value =+ element
        });
    }

})
</script>

<template>
    <Head title="Users" />

    <AppLayout>
        <ContentLayout v-if="user?.role === 'admin'" content_title="Users Management">
            <template #content-head-buttons>
                <button @click="router.get('#')" class="flex items-center bg-blue-600 text-white px-2 py-2 rounded-lg hover:bg-opacity-90 transition">
                    <i data-feather="plus" class="mr-2 text-xs"></i><span>Add User</span>
                </button>
            </template>

            <template #main-content>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 overflow-y-auto p-2">
                    <div class="sm:col-span-2 col-span-3">
                        <div class="flex flex-col border border-gray-400 rounded-lg shadow-md bg-white mb-2">
                            <div class="p-3 flex justify-between">
                                <h2 class="text-xl font-semibold">Membership requests <span id="request-count" class="text-blue-500">{{ memberReqCount }}</span></h2>
                                <button @click="router.get(route('memberships'))" class="px-4 border-none text-lg text-blue-500 font-semibold hover:text-blue-400">See all</button>
                            </div>
                            <div class="px-8 py-2 mb-2 max-h-80 overflow-auto">
                                <div id="member-request-container" class="flex flex-col gap-2">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="flex flex-row gap-2 rounded-lg border border-gray-400 shadow-md bg-white">
                        
                    </div> -->
                    <div class="col-span-3 bg-white border border-gray-400 shadow-md rounded-lg">
                        <div class="py-2 px-3 mt-2">
                            <h2 class="text-xl font-semibold">Users</h2>
                        </div>
                        
                        <div id="user-container" class="grid grid-cols-1 lg:grid-cols-2 p-4 gap-2">
                            
                        </div>
                        <div class="mt-5 text-xxs sm:text-sm px-4">
                            <nav id="pagination" aria-label="Pagination Navigation" class="inline-flex items-center space-x-2 text-sm font-semibold"></nav>
                            <div id="pagination-meta" class="mt-2 mb-4"></div>
                        </div>
                    </div>
                </div>
            </template>
        </ContentLayout>
        <Unauthorized v-else />
    </AppLayout>
</template>