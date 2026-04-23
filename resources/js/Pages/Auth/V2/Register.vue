<script setup>
import InputLabel from '@/Components/Input/InputLabel.vue';
import TextInput from '@/Components/Input/TextInput.vue';
import GuestLayoutV2 from '@/Layouts/GuestLayoutV2.vue';
import InputError from '@/Components/Breeze/InputError.vue';
import SecondaryButton from '@/Components/Breeze/SecondaryButton.vue';
import SubmitButton from '@/Components/Button/SubmitButton.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Register" />

    <GuestLayoutV2>
        <form @submit.prevent="submit" class="w-full">
            <div class="text-center">
                <h2 class="text-xl font-bold text-gray-50">Register</h2>
            </div>
            <div class="px-5">
                <InputLabel label-for="Name" />
                <TextInput
                    id="name"
                    type="text"
                    class="bg-white/20 focus:border-white shadow-md"
                    v-model="form.name"
                    required
                    autocomplete="username"
                />
                <InputError class="mt-2 rounded-lg px-2 bg-white/60" :message="form.errors.name" />
            </div>
            <div class="px-5 mt-3">
                <InputLabel label-for="Email" />
                <TextInput
                    id="email"
                    type="email"
                    class="bg-white/20 focus:border-white shadow-md"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />
                <InputError class="mt-2 rounded-lg px-2 bg-white/60" :message="form.errors.email" />
            </div>
            <div class="px-5 mt-3">
                <InputLabel label-for="Password" />
                <TextInput
                    id="password"
                    type="password"
                    class="bg-white/20 focus:border-white shadow-md"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />
                <InputError class="mt-2 rounded-lg px-2 bg-white/60" :message="form.errors.password" />
            </div>
            <div class="px-5 mt-3">
                <InputLabel label-for="Confirm Password" />
                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="bg-white/20 focus:border-white shadow-md"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />
                <InputError class="mt-2 rounded-lg px-2 bg-white/60" :message="form.errors.password_confirmation" />
            </div>
             <div class="px-5 mt-4">
                <SubmitButton 
                    class="bg-gray-50 rounded-xl tracking-wider font-bold uppercase shadow-md border-none text-blue-800 hover:bg-gray-200"
                    :class="{ 'opacity-25' :form.processing}"
                    :disabled="form.processing"
                >
                    Register
                </SubmitButton>
            </div>
            <div class="px-4 mt-4 flex items-center gap-2 text-gray-100">
                <span class="border-t border-gray-300 w-full"></span>
                <span>or</span>
                <span class="border-t border-gray-300 w-full"></span>
            </div>
            <div class="flex flex-col gap-4 items-center p-4">
                <span class="uppercase text-gray-100">Register with</span>
                <div class="flex flex-wrap gap-2">
                    <SecondaryButton @click="router.get('#')" class="shadow-md">
                        <svg version="1.1" width="20" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                            <path style="fill:#FBBB00;" d="M113.47,309.408L95.648,375.94l-65.139,1.378C11.042,341.211,0,299.9,0,256 c0-42.451,10.324-82.483,28.624-117.732h0.014l57.992,10.632l25.404,57.644c-5.317,15.501-8.215,32.141-8.215,49.456 C103.821,274.792,107.225,292.797,113.47,309.408z"></path>
                            <path style="fill:#518EF8;" d="M507.527,208.176C510.467,223.662,512,239.655,512,256c0,18.328-1.927,36.206-5.598,53.451 c-12.462,58.683-45.025,109.925-90.134,146.187l-0.014-0.014l-73.044-3.727l-10.338-64.535 c29.932-17.554,53.324-45.025,65.646-77.911h-136.89V208.176h138.887L507.527,208.176L507.527,208.176z"></path>
                            <path style="fill:#28B446;" d="M416.253,455.624l0.014,0.014C372.396,490.901,316.666,512,256,512 c-97.491,0-182.252-54.491-225.491-134.681l82.961-67.91c21.619,57.698,77.278,98.771,142.53,98.771 c28.047,0,54.323-7.582,76.87-20.818L416.253,455.624z"></path>
                            <path style="fill:#F14336;" d="M419.404,58.936l-82.933,67.896c-23.335-14.586-50.919-23.012-80.471-23.012 c-66.729,0-123.429,42.957-143.965,102.724l-83.397-68.276h-0.014C71.23,56.123,157.06,0,256,0 C318.115,0,375.068,22.126,419.404,58.936z"></path>
                        </svg>
                    </SecondaryButton>
                    <SecondaryButton @click="router.get('#')" class="shadow-md">
                        <i class="fa-brands fa-facebook text-2xl text-blue-600"></i>
                    </SecondaryButton>
                </div>
            </div>
            <div class="px-2 mt-5">
                <p class="text-gray-50">Already have account? <Link :href="route('login')" class="underline hover:text-blue-100">login here.</Link></p>
            </div>
        </form>
    </GuestLayoutV2>
</template>