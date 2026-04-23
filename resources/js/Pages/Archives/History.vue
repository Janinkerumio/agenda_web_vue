<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ContentLayout from '@/Layouts/ContentLayout.vue';
import Unauthorized from '@/Components/Placeholder/Unauthorized.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage()
const user = computed(() => page.props.auth?.user )
</script>

<template>
    <Head title="History" />

    <AppLayout>
        <ContentLayout v-if="user?.role === 'admin'" content_title="History">
            <template #content-head-buttons>

            </template>
            <template #main-content>

            </template>
        </ContentLayout>
        <Unauthorized v-else />
    </AppLayout>
</template>