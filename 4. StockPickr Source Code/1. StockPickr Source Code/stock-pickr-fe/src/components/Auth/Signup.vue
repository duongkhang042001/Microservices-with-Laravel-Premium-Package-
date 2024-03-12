<template>
<section class="section default-bg-color">
    <div class="wrapper text-center">
        <form class="form-signin font-roboto">
            <h1 class="h3 mb-3 font-weight-normal">Sign Up</h1>

            <input type="text" id="name" v-model="name" class="form-control input-slick" placeholder="Full Name" required autofocus>
            <input type="email" id="email" v-model="email" class="form-control input-slick" placeholder="E-mail address" required>

            <input type="password" id="password" v-model="password" class="form-control input-slick" placeholder="Password" required>
            <input type="password" id="passwordConfirm" v-model="passwordConfirm" class="form-control input-slick" placeholder="One more time please" required>

            <button @click.prevent="signUp" class="btn btn-slick mt-4" type="submit">Sign Up</button>
        </form>
    </div>
</section>
</template>

<script lang="ts">
import { ref, Ref } from 'vue';
import AuthService from '@/services/AuthService';
import router from '@/router';
import ExceptionDisplayService from '@/services/ExceptionDisplayService';
import ToastService from '@/services/ToastService';

export default {
    name: 'signup',

    setup() {
        const name: Ref<string> = ref('');
        const email: Ref<string> = ref('');
        const password: Ref<string> = ref('');
        const passwordConfirm: Ref<string> = ref('');

        const authService = new AuthService();
        const exceptionDisplay = new ExceptionDisplayService();
        const toastService = new ToastService();

        const signUp = async () => {
            try {
                await authService.signUp(name.value, email.value, password.value, passwordConfirm.value);
                toastService.succes('Welcome among the Stock Pickrs', 'Congratulations!');

                await router.push('/');
            } catch (err) {
                exceptionDisplay.display(err);
            }
        };

        return {
            signUp,
            name,
            email,
            password,
            passwordConfirm
        };
    }
}
</script>

<style scoped>
.form-signin {
  width: 100%;
  max-width: 330px;
  padding: 15px;
  margin: 0 auto;
}
.form-signin .form-control {
  position: relative;
  box-sizing: border-box;
  height: auto;
  padding: 10px;
  font-size: 16px;
}
.form-signin .form-control:focus {
  z-index: 2;
}
.bt-0 {
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}
.bb-0 {
  border-bottom-left-radius: 0;
  border-bottom-right-radius: 0;
}
</style>
