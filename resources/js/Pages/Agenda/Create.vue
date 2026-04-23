<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ContentLayout from '@/Layouts/ContentLayout.vue';
import Unauthorized from '@/Components/Placeholder/Unauthorized.vue';
import Success from '@/Components/Alert/Success.vue';
import Errors from '@/Components/Alert/Errors.vue';
import { Head, usePage, useForm, router } from '@inertiajs/vue3';
import { computed } from 'vue';
import FormLayout from '@/Layouts/FormLayout.vue';
import InputLabel from '@/Components/Input/InputLabel.vue';
import TextInput from '@/Components/Input/TextInput.vue';
import TextAreaInput from '@/Components/Input/TextAreaInput.vue';
import FileInput from '@/Components/Input/FileInput.vue';
import CancelButton from '@/Components/Button/CancelButton.vue';
import SubmitButton from '@/Components/Button/SubmitButton.vue';

const page = usePage()
const user = computed(() => page.props.auth?.user )

const form = useForm({
    title: '',
    date: '',
    notes: '',
    file_path: null
})

const submitAgenda = () => {
    form.post('/agendas/submit', {
        forceFormData: true,
        onSuccess: () => {
            form.reset()
        },
    })
}
</script>

<template>
    <Head title="Create Agenda" />
    <AppLayout>
        <ContentLayout v-if="user?.role === 'admin'" content_title="Create Agenda">
            <template #main-content>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-2 overflow-y-auto">
                    <div class="p-3 col-span-2">
                        <div class="mb-3">
                            <Success />
                            <Errors />
                        </div>
                        <form @submit.prevent="submitAgenda">
                            <FormLayout form-title="Add New Agenda">
                                <template #fields>
                                    <div>
                                        <InputLabel label-for="Title"/>
                                        <TextInput
                                            type="text"
                                            v-model="form.title"
                                            id="title"
                                            name="title"
                                            required
                                        />
                                    </div>
                                    <div>
                                        <InputLabel label-for="Date" />
                                        <TextInput
                                            type="date"
                                            v-model="form.date"
                                            id="date"
                                        />
                                    </div>
                                    <div class="md:col-span-2">
                                        <InputLabel label-for="Notes" />
                                        <TextAreaInput
                                            name="notes"
                                            v-model="form.notes"
                                        />
                                    </div>
                                    <div class="md:col-span-2">
                                        <InputLabel label-for="File Attachment" />
                                        <FileInput
                                            @input="form.file_path = $event.target.files[0]"
                                        />
                                    </div>
                                </template>
                                <template #buttons>
                                    <CancelButton @click="router.get(route('agenda.view-all'))">Cancel</CancelButton>
                                    <SubmitButton :disabled="form.processing">
                                        {{ form.processing ? 'Saving...' : 'Save Agenda' }}
                                    </SubmitButton>
                                </template>
                            </FormLayout>
                        </form>
                    </div>
                </div>
            </template>
        </ContentLayout>
        <Unauthorized v-else />
    </AppLayout>
</template>