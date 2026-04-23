<script setup>
import Navigations from '@/Components/Navigations.vue';
import ConfirmModal from '@/Components/Modal/ConfirmModal.vue';
import Notifications from '@/Components/Alert/Notifications.vue';
import AppHeader from '@/Components/Header/AppHeader.vue';
import { usePage } from '@inertiajs/vue3';
import { onMounted, computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth?.user);

</script>

<template>
    <main class="bg-gray-100 min-h-screen">
        <div class="flex h-screen overflow-hidden">

            <Navigations/>

            <div class="overlay" id="overlay"></div>

            <div class="flex-1 flex flex-col overflow-hidden">
                <AppHeader />
                <section class="flex-1 overflow-y-auto bg-gray-50 p-6">
                    <slot />
                </section>


            </div>

            <ConfirmModal/>
            <Notifications/>

            <div v-if="user === 'user'">

            </div>
            <div v-else-if="user === 'admin'">

            </div>

        </div>
    </main>
</template>