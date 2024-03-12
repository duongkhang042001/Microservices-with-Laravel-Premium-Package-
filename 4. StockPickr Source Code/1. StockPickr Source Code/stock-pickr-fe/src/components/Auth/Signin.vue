<template>
<section class="section default-bg-color">
    <div class="wrapper text-center">
        <form class="form-signin font-roboto">
            <h1 class="h3 mb-3 font-weight-normal">Sign In</h1>

            <input type="email" id="email" v-model="email" class="form-control mt-3 input-slick" placeholder="E-mail address" required autofocus>
            <input type="password" id="password" v-model="password" class="form-control mt-3 mb-4 input-slick" placeholder="Password" required>

            <button @click.prevent="signIn" class="btn btn-slick mt-4" type="submit">Sign In</button>
        </form>
    </div>
</section>
</template>

<script lang="ts">
import { ref, Ref } from 'vue';
import AuthService from '@/services/AuthService';
import router from '@/router';
import ExceptionDisplayService from '@/services/ExceptionDisplayService';

export default {
    name: 'signin',

    setup() {
        const email: Ref<string> = ref('');
        const password: Ref<string> = ref('');

        const authService = new AuthService();
        const exceptionDisplay = new ExceptionDisplayService();

        const signIn = async () => {
            try {
                await authService.signIn(email.value, password.value);
                await router.push('/leaderboard');
            } catch (err) {
                exceptionDisplay.display(err);
            }
        };

        return {
            signIn,
            email,
            password
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
.form-signin .checkbox {
  font-weight: 400;
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
.form-signin input[type="email"] {
  margin-bottom: -1px;
}
.form-signin input[type="password"] {
  margin-bottom: 10px;
}
</style>
