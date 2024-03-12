<template>
<section class="section default-bg-color">
    <div class="wrapper text-center">
        <form class="font-roboto">
            <h1 class="h3 mb-3 font-weight-normal">Edit profile</h1>

            <input type="text" v-model="name" class="form-control input-slick" placeholder="Full name" required autofocus>
            <input type="email" v-model="email" :disabled="true" class="form-control input-slick" placeholder="Email address" required>

            <input type="password" v-model="password" class="form-control input-slick" placeholder="New password" required>
            <input type="password" v-model="passwordConfirm" class="form-control input-slick" placeholder="One more time please" required>

            <button @click.prevent="save" class="btn btn-slick mt-2" type="submit">Save</button>
            <button @click.prevent="deleteAccount" class="btn btn-danger-slick btn-block mt-2">Delete account</button>
        </form>

        <ConfirmDeleteAccount @confirmed="onConfirmed($event)" :show="showModal" @close="showModal = false" />
    </div>
</section>
</template>

<script lang="ts">
import { ref, Ref } from 'vue';
import store from '@/store/Store';
import { User } from '@/models/User';
import UserService from '@/services/UserService';
import ToastService from '@/services/ToastService';
import ConfirmDeleteAccount from '@/components/User/Modals/ConfirmDeleteAccount.vue';
import ExceptionDisplayService from '@/services/ExceptionDisplayService';
import router from '@/router';

export default {
    name: 'edit-profile',
    components: { ConfirmDeleteAccount },

    setup() {
        const user: User = store.getters.getUser;

        const name: Ref<string> = ref(user.name);
        const email: Ref<string> = ref(user.email);
        const password: Ref<string> = ref('');
        const passwordConfirm: Ref<string> = ref('');
        const showModal: Ref<boolean> = ref(false);

        const userService = new UserService();
        const toastService = new ToastService();
        const exceptionDisplay = new ExceptionDisplayService();

        const save = async () => {
            try {
                await userService.updateUser(
                    user.id,
                    name.value,
                    password.value,
                    passwordConfirm.value
                );

                toastService.succes('Your profile was updated!', 'Success');
                await router.push('/');
            } catch (err) {
                exceptionDisplay.display(err);
            }
        };

        const deleteAccount = async () => {
            showModal.value = true;
        };

        const onConfirmed = async (password: string) => {
            try {
                await userService.deleteUser(password);
                toastService.succes('We are glad, you were here! Good luck for picking the right stocks!', 'Goodbye')
                await router.push('/');
            } catch (err) {
                exceptionDisplay.display(err);
            }
        };

        return {
            save,
            name,
            email,
            password,
            passwordConfirm,
            deleteAccount,
            showModal,
            onConfirmed
        };
    }
}
</script>

<style scoped>
form {
  width: 100%;
  max-width: 330px;
  padding: 15px;
  margin: 0 auto;
}
form .form-control {
  position: relative;
  box-sizing: border-box;
  height: auto;
  padding: 10px;
  font-size: 16px;
}
form .form-control:focus {
  z-index: 2;
}
</style>
