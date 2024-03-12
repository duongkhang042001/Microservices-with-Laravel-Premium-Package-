<template>
<div class="container pt-5 footer font-roboto">
    <div class="row pb-4">
        <div class="col-lg-4 text-center">
            <h6 class="footer-title text-uppercase">About StockPickr</h6>
            <p class="mt-3 about-footer-color desc-line-height">
                StockPickr is a platform where you can find 2000+ companies with financial statements, metrics, charts and scores based on the company's performance
            </p>
        </div>
        <div class="col-lg-4 text-center">
            <h6 class="footer-title text-uppercase">Important Link</h6>
            <ul class="list-unstyled footer-menu-list margin-t-30">
                <li><a href="">Terms & Condition</a></li>
                <li><a href="">Privacy Policy</a></li>
            </ul>
        </div>
        <div class="col-lg-4 text-center">
            <h6 class="footer-title text-uppercase">Our Pages</h6>
            <ul class="list-unstyled footer-menu-list margin-t-30">
                <li>
                    <router-link :to="{name: 'main-page', hash: '#landing'}">Home</router-link>
                </li>
                <li>
                    <router-link :to="{name: 'main-page', hash: '#features'}">Features</router-link>
                </li>
                <li>
                    <router-link :to="{name: 'main-page', hash: '#price'}">Pricing</router-link>
                </li>
                <li v-if="!$store.getters.isSignedIn">
                    <router-link :to="{name: 'signIn'}">Sign In</router-link>
                </li>
                <li v-if="!$store.getters.isSignedIn">
                    <router-link :to="{name: 'signUp'}">Sign Up</router-link>
                </li>
            </ul>
        </div>
    </div>
    <div class="footer-border"></div>
    <div class="row">
        <div class="col-sm-12">
            <div class="footer-alt text-center">
                <p class="copy-rights">{{ getYear() }} &copy; StockPickr</p>
            </div>
        </div>
    </div>
    </div>
</template>

<script>
export default {
    name: 'Footer',
    setup() {
        const getYear = () => new Date().getFullYear();

        return {
            getYear
        };
    }
}
</script>

<style lang="scss" scoped>
.footer-title {
    color: #fff
}

.about-footer-color {
    color: #989898
}
.footer ul.footer-menu-list li a:hover {
    color: #fff !important;
    transition: all .3s
}

.footer ul.footer-menu-list li a {
    color: rgba(255, 255, 255, .5) !important;
    display: inline-block;
    padding: 5px 0;
    text-decoration: none !important;
    border: none !important;
}

.footer-border {
    background-color: rgba(255, 255, 255, .2);
    height: 1px
}

.footer-alt .copy-rights {
    margin: 10px 0;
    line-height: 60px;
    color: #989898;
    padding-left: 20px
}
</style>
