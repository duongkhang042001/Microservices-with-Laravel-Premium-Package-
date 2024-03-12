<template>
<Modal v-if="show">
    <template v-slot:content>
        <h3 class="text-center">Are you sure?</h3>
        <div class="fs-16 pt-2">Your account will be deleted forever. You will no longer be able to login. However you can create a new account with the same e-mail any time you want.</div>

        <div class="row justify-content-center pt-4">
            <div class="col-6 text-center">
                <input type="password" v-model="password" class="form-control input-slick" placeholder="Type your password please">
            </div>
        </div>

        <div class="row justify-content-center text-align-center mt-4 pt-3">
            <div class="col-auto pl-1">
                <button class="btn btn-danger-slick" @click="onDelete">Delete account</button>
            </div>
            <div class="col-auto pl-1">
                <button class="btn btn-link" @click="onClose">Cancel</button>
            </div>
        </div>
    </template>
</Modal>
</template>

<script lang="ts">
import Modal from '@/components/Modal.vue';
import { ref, Ref, SetupContext } from 'vue';
import { ModalProps } from '@/models/Props';

export default {
    name: 'ConfirmDeleteAccount',
    components: { Modal },
    props: {
        show: {
            type: Boolean,
            required: true
        }
    },
    emits: {
        close: null,
        confirmed(password: string) {
            return password.length !== 0;
        }
    },

    setup(props: ModalProps, context: SetupContext) {
        const password: Ref<string> = ref('');

        const onClose = () => {
            context.emit('close');
        };

        const onDelete = () => {
            context.emit('confirmed', password.value);
        };

        return {
            password,
            onClose,
            onDelete
        };
    }
}
</script>
