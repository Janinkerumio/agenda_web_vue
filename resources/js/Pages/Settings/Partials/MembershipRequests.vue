<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import Errors from '@/Components/Alert/Errors.vue';
import Success from '@/Components/Alert/Success.vue';
import MembershipRequestForm from './MembershipRequestForm.vue';

const page = usePage();
const user = computed(() => page.props.auth?.user )

const showModal = ref(false)

function openModal() {
    showModal.value = true
}

function closeModal() {
    showModal.value = false
}
</script>

<template>
    <section class="space-y-6 max-w-full">
        <div class="bg-blue-100 dark:bg-opacity-25 rounded-md w-full">
            <div class="flex rounded-br-3xl bg-blue-200 dark:bg-opacity-25 w-64 max-w-full">
                <h2 class="text-2xl text-blue-700 dark:text-blue-100 font-bold p-2 ml-4">
                    {{ user?.name }}
                </h2>
            </div>

            <div v-if="user?.role === 'user'">
                <div class="p-2 mb-3">
                    <div class="flex flex-row items-center gap-2 p-2 text-lg">
                        <span class="text-gray-700 dark:text-gray-100 font-medium">Authority:</span>
                        <span class="text-slate-600 dark:text-gray-300 font-semibold">{{ user?.role }}</span>
                    </div>
                    <p class="p-2 text-gray-800 dark:text-gray-100">You're logged in as a guest. Getting a membership gives you access to,</p>
                    <ul class="ml-3 mt-2 text-gray-700 dark:text-gray-200">
                        <li><i class="fa-solid fa-check text-green-500 text-lg px-4"></i>Raising a concern</li>
                        <li><i class="fa-solid fa-check text-green-500 text-lg px-4"></i>View comments</li>
                        <li><i class="fa-solid fa-check text-green-500 text-lg px-4"></i>Adding a comment</li>
                    </ul>
                </div>

                <button type="button" @click="openModal" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent dark:border-blue-400 rounded-md font-semibold text-xs text-white mb-2 ml-2 uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150'">
                    Request Membership
                </button>
                <div class="p-3">
                    <Success />
                    <Errors />
                </div>
            </div>
            
            <div v-if="['admin', 'member'].includes(user?.role)" class="p-2">
                <div class="flex flex-row items-center gap-2 p-2 text-lg">
                    <span class="text-gray-700 font-medium">Authority:</span>
                    <span class="text-slate-600 font-semibold">{{ user?.role }}</span>
                </div>
                <div class="flex flex-col p-2">
                    <h3 v-if="['admin', 'member'].includes(user?.role)" class="text-gray-600 font-medium text-lg">Role: <span class="text-gray-600">{{ user?.specific_role }}</span></h3>
                    <ul v-if="user?.role === 'admin'" class="ml-3 mt-2 text-gray-700">
                        <li><i class="fa-solid fa-check text-green-500 text-lg px-4"></i>Full application access</li>
                        <li><i class="fa-solid fa-check text-green-500 text-lg px-4"></i>Overall data override</li>
                        <li><i class="fa-solid fa-check text-green-500 text-lg px-4"></i>Generate reports and archiving data</li>
                        <li><i class="fa-solid fa-check text-green-500 text-lg px-4"></i>Manage accounts</li>
                    </ul>
                    <ul v-if="user?.role === 'member'" class="ml-3 mt-2 text-gray-700">
                        <li><i class="fa-solid fa-check text-green-500 text-lg px-4"></i>Calendar events viewing</li>
                        <li><i class="fa-solid fa-check text-green-500 text-lg px-4"></i>Raise concerns</li>
                        <li><i class="fa-solid fa-check text-green-500 text-lg px-4"></i>Manage concerns</li>
                        <li><i class="fa-solid fa-check text-green-500 text-lg px-4"></i>Add comments</li>
                    </ul>
                </div>
            </div>
        </div>
        <MembershipRequestForm :show="showModal" :user-id="user?.id" @close="closeModal" />
    </section>
</template>