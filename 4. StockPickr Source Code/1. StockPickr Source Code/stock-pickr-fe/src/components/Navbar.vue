<template>
<nav class="navbar navbar-dark navbar-expand-lg fixed-top custom-nav sticky">
    <div class="container">

        <router-link :to="{ name: 'main-page', hash: '#landing'}" class="navbar-brand logo">
            <img src="img/logo-2.png" alt="" class="img-fluid" height="29">
        </router-link>

        <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse font-roboto" id="navbarCollapse">
            <ul v-if="$store.getters.isSignedIn" class="navbar-nav ml-auto">
                <li class="nav-item">
                    <router-link :to="{ name: 'main-page', hash: '#landing'}" class="nav-link">
                        Home
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link :to="{ name: 'leaderboard'}" class="nav-link">
                        Leaderboard
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link :to="{ name: 'edit-profile'}" class="nav-link">
                        Edit profile
                    </router-link>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" @click.prevent="signOut">Logout</a>
                </li>
            </ul>

            <ul v-else class="navbar-nav ml-auto">
                <li class="nav-item">
                    <router-link :to="{ name: 'main-page', hash: '#landing'}" class="nav-link">
                        Home
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link :to="{ name: 'main-page', hash: '#features'}" class="nav-link">
                        Features
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link :to="{ name: 'main-page', hash: '#price'}" class="nav-link">
                        Pricing
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link :to="{ name: 'signIn'}" class="nav-link">
                        Sign In
                    </router-link>
                </li>
                <li class="nav-item signup btn-green">
                    <router-link :to="{ name: 'signUp'}" class="nav-link">
                        Sign Up
                    </router-link>
                </li>
            </ul>
        </div>
    </div>
</nav>
</template>

<script>
import AuthService from '@/services/AuthService'
import router from '@/router';

export default {
    name: 'navbar',
    setup() {
        const authService = new AuthService();

        const signOut = async () => {
            await authService.signOut();
            await router.push('/');
        };

        return {
            signOut
        };
    }
}
</script>

<style lang="scss" scoped>
.router-link-exact-active {
    color: rgba(255, 255, 255, .6) !important;
    border: none !important;
}
img {
    width: 150px;
}

.custom-nav {
    padding: 15px 0;
    width: 100%;
    border-radius: 0;
    z-index: 999;
    border-bottom: 1px solid rgba(255, 255, 255, .07);
    margin-bottom: 0;
    transition: all .5s ease-in-out;
    background-color: transparent
}

.custom-nav.sticky {
    background-color: #272a33;
    padding: 12px 0;
    border-bottom: 1px solid #272a33
}

.custom-nav .navbar-nav li a {
    color: rgba(255, 255, 255, .6);
    font-size: 15px;
    background-color: transparent!important;
    padding: 10px 0;
    margin: 0 7px;
    transition: all .4s
}

.custom-nav .navbar-nav li.active a,
.custom-nav .navbar-nav li:not(.signup) a:hover,
.custom-nav .navbar-nav li:not(.signup) a:active {
    color: white !important
}

.custom-nav .btn-custom {
    margin-top: 5px;
    margin-bottom: 5px
}

.custom-nav .navbar-brand.logo img {
    height: 32px
}

.navbar-toggler {
    font-size: 24px;
    margin-top: 5px;
    margin-bottom: 0;
    color: #fff
}

.signup {
    border-radius: 25px;

    a {
        color: white !important;
    }
}

@media(max-width:768px) {
    .custom-nav {
        margin-top: 0;
        padding: 10px 0!important;
        background-color: #272a33;
        border-bottom: 1px solid rgba(90, 90, 90, .24)
    }
    .custom-nav .navbar-nav li a {
        margin: 0 0;
        padding: 6px 0
    }
    .custom-nav>.container {
        width: 90%
    }
    .navbar-nav {
        margin-top: 0
    }
    .navbar-toggler {
        font-size: 24px;
        margin-top: 4px;
        margin-bottom: 0;
        color: #fff
    }
    .custom-nav .navbar-brand.logo img {
        height: 22px
    }
    .signup {
        width: 70px;
        border-radius: 20px;
    }
}
// menu toggle
@media(max-width:976px) {
    .signup {
        width: 85px;
        border-radius: 20px;
    }
}
</style>
