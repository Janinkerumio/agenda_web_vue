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
import Selection from '@/Components/Input/Selection.vue';
import HeadButton from '@/Components/Button/HeadButton.vue';

const props = defineProps({
    agenda: Object,
    res_pers: Object
})
const page = usePage()
const user = computed(() => page.props.auth?.user )

const form = useForm({
    agenda_id: props.agenda.agenda_id,
    description: '',
    responsible_person_id: user.role === 'admin' ? page.props.auth.user.id : '',
    status: '',
    due_date: null,
    file: null,
})

const submitConcern = () => {
    form.post('/concerns/submit', {
        forceFormData: true,
        onSuccess: () => {
            form.reset()
        },
    })
}
</script>

<template>
    <Head title="Raise Concern" />
    <AppLayout>
        <ContentLayout v-if="user?.role === 'admin'" content_title="Raise Concern">
            <template #main-content>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-2 overflow-y-auto">
                    <div class="p-3 col-span-2">
                        <div class="mb-3">
                            <Success :exit-action="() => { router.get(route('agenda.view', agenda?.agenda_id)) }"/>
                            <Errors />
                        </div>
                        <form v-if="['admin', 'member'].includes(user?.role)" @submit.prevent="submitConcern">
                            <FormLayout :form-title="'Add Concern/Issue for ' + agenda?.title">
                                <template #fields>
                                    <div class="col-span-2">
                                        <InputLabel label-for="Description"/>
                                        <TextAreaInput
                                            name="notes"
                                            v-model="form.description"
                                            required
                                        />
                                    </div>
                                    <div>
                                        <InputLabel label-for="Date" />
                                        <TextInput
                                            type="date"
                                            v-model="form.date"
                                            id="date"
                                            required
                                        />
                                    </div>
                                    <div>
                                        <InputLabel label-for="Responsible Person" />
                                        <TextInput v-if="!user.role === 'admin'"
                                            type="text"
                                            v-model="form.responsible_person"
                                            disabled
                                        />
                                        <Selection v-else 
                                            initial-val-label="-- Select Responsible --"
                                            initial-value=""
                                            v-model="form.responsible_person_id"
                                            required
                                        >
                                            <option v-for="(responsible, id) in res_pers" 
                                                :key="id" 
                                                :value="id"
                                            >
                                                {{ responsible }}
                                            </option>
                                        </Selection>
                                    </div>
                                    <div>
                                        <InputLabel label-for="Status" />
                                        <Selection 
                                            initial-val-label="Pending"
                                            initial-value="pending"
                                            v-model="form.status"
                                            required
                                        >
                                            <option v-if="user.role === 'admin'" value="completed">Completed</option>
                                        </Selection>
                                    </div>
                                    <div class="md:col-span-2">
                                        <InputLabel label-for="File Attachment" />
                                        <FileInput
                                            @input="form.file_path = $event.target.files[0]"
                                        />
                                    </div>
                                </template>
                                <template #buttons>
                                    <CancelButton @click="router.get(route('agenda.view', agenda?.agenda_id))">Cancel</CancelButton>
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