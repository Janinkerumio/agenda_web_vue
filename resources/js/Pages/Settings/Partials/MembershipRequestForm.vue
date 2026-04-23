<script setup>
import { useForm } from '@inertiajs/vue3'
import Modal from '@/Components/Breeze/Modal.vue'
import InputLabel from '@/Components/Input/InputLabel.vue'
import TextInput from '@/Components/Input/TextInput.vue'
import SubmitButton from '@/Components/Button/SubmitButton.vue'
import CancelButton from '@/Components/Button/CancelButton.vue'

const props = defineProps({
    show: Boolean,
    userId: [Number, String]
})

const emit = defineEmits(['close'])

const form = useForm({
    name: '',
    s_role: '',
    user_id: props.userId,
})

function submitRequest() {
    form.post(route('submit-mbr-rqst'), {
        onSuccess: () => {
            form.reset()          
            emit('close')         
        },
        onError: () => {
            emit('close')
        }
    })
}

function closeModal() {
    form.reset()
    form.clearErrors()
    emit('close')
}
</script>

<template>
    <Modal :show="show" @close="closeModal">
        <div class="p-6">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-50 mb-4">Request Membership Access</h2>
            <form @submit.prevent="submitRequest" class="space-y-4">
                <div>
                    <InputLabel>Full name <span class="text-red-700">*</span></InputLabel>
                    <TextInput 
                        v-model="form.name" 
                        type="text" 
                        id="name" 
                        name="name" 
                        placeholder="Enter your full name" 
                        required
                    />
                </div>
                <div>
                    <InputLabel for="s_role" labelFor="Department/Specific Role" />
                    <TextInput 
                        v-model="form.s_role" 
                        type="text" 
                        id="s_role" 
                        name="s_role" 
                        placeholder="e.g., IT, HR, Finance"
                        required
                    />
                </div>
                <SubmitButton :disabled="form.processing">
                    {{ form.processing ? 'Submitting...' : 'Submit Membership Request' }}
                </SubmitButton>
                <CancelButton @click="closeModal">
                    Cancel
                </CancelButton>
            </form>
            <p class="text-sm text-gray-500 mt-4">Your request will be reviewed by the system administrator.</p>
        </div>
    </Modal>
</template>