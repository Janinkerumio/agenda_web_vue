<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ContentLayout from '@/Layouts/ContentLayout.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import MembershipRequests from './Partials/MembershipRequests.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Profile Settings" />

    <AppLayout>
        <ContentLayout>
            <template #main-content>
                <div class="py-12">
                    <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                        <div class="bg-white p-4 shadow sm:rounded-lg dark:bg-gray-800 max-w-2xl">
                            <div class="max-w-full">
                                <MembershipRequests class="max-w-xl" />
                            </div>
                        </div>
                        <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800">
                            <UpdateProfileInformationForm
                                :must-verify-email="mustVerifyEmail"
                                :status="status"
                                class="max-w-xl"
                            />
                        </div>
                        <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800">
                            <UpdatePasswordForm class="max-w-xl" />
                        </div>
                        <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800">
                            <DeleteUserForm class="max-w-xl" />
                        </div>
                    </div>
                </div>
            </template>
        </ContentLayout>
    </AppLayout>
</template>