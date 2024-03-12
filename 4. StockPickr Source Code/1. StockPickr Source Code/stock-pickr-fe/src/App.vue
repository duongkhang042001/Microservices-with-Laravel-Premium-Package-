<template>
    <Navbar />
    <router-view v-slot="{ Component }">
        <transition name="slide-fade" mode="out-in">
            <component :is="Component" />
        </transition>
    </router-view>
    <Footer />
</template>

<script lang="ts">
import Navbar from '@/components/Navbar.vue';
import Footer from '@/components/MainPage/Footer.vue';

export default {
    name: 'app',
    components: { Navbar, Footer }
}
</script>


<style lang="scss">
@import url('https://fonts.googleapis.com/css2?family=Wallpoet&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Iceland&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@100;300&display=swap');

// Bootstrap fix
.navbar-collapse.collapse.in {
  display: block!important;
}

ul.navbar-nav {
    margin: 0 auto;
}

.light-hover tbody tr:hover {
    background-color: rgba(255, 255, 255, 0.07);
}

.h-100vh {
    min-height: 100vh
}

.wrapper {
  display: -ms-flexbox;
  display: -webkit-box;
  display: flex;
  -ms-flex-align: center;
  -ms-flex-pack: center;
  -webkit-box-align: center;
  align-items: center;
  -webkit-box-pack: center;
  justify-content: center;
  padding-top: 40px;
  padding-bottom: 40px;
}

.fw-100 {
    font-weight: 100 !important;
}
.fw-300 {
    font-weight: 300;
}

.input-slick {
    text-align: center;
    margin-bottom: 5vh !important;
    border-right: none !important;
    border-top: none !important;
    border-left: none !important;
    border-bottom: 2px solid rgba(255, 255, 255, .1) !important;
    border-radius: 0 !important;
    background-color: transparent !important;
    outline: none !important;
    color: white !important;
    caret-color: white !important;
}

.input-slick::placeholder {
    text-align: center !important;
    color: rgba(255, 255, 255, .5) !important;
}

.input-slick:focus {
    background-color: transparent !important;
    border-color: inherit !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
    color: white !important;
}

.input-slick:disabled {
    color: rgba(255, 255, 255, .5) !important;
}

.btn-slick {
    background: rgba(255, 255, 255, .1) !important;
    width: 100% !important;
    padding: 2vh !important;
    border: none !important;
    cursor: pointer !important;
    border-radius: 4px 4px !important;
}

.btn-slick:hover {
    background: rgba(255, 255, 255, .2) !important;
}

.default-bg-color {
    background-color: #2e323a;
    // background-color: #282832; Atom
}

.box {
    border-radius: 10px !important;
    background: rgba(56, 60, 68, 0.8) !important;
    // background-color: #383c44 !important;

    // background-color: #303642 !important;
    // background-color: #32323e !important; Atom
    // background: rgba(0, 0, 0, 0.15) !important;
}

.section {
    padding-top: 80px;
    padding-bottom: 80px;
    position: relative;
}

.btn-green {
    background-color: #2ed078 !important;
}

.slide-fade-enter-active {
    transition: all 0.3s ease;
}

.slide-fade-leave-active {
    transition: all 0.3s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}

.slide-fade-enter-from {
    transform: translateX(70px);
    opacity: 0;
}

.slide-fade-leave-to {
    transform: translateX(70px);
    opacity: 0;
}

.mb-6 {
    margin-bottom: ($spacer * 6) !important;
}
.mt-6 {
    margin-top: ($spacer * 6) !important;
}

html {
    overflow-y: scroll;
}

a {
    cursor: pointer;
}

a:hover {
    text-decoration: none !important;
}

// Felső, oldaltól független navigáció
// nav.header {
//     padding-left: 0 !important;
//     padding-right: 0 !important;
//     background-color: #303030 !important;
// }

ul.nav {
    margin: 0 auto !important;
}

.nav-item {
    padding: 5px 10px !important;
}

.nav-link:hover {
    cursor: pointer;
    background-color: #222;
}

.nav-link.active {
    background-color: #5f81ff !important;
}

.table {
    margin: auto;
    font-size: 14px;
}
.table td {
    text-align: center;
}
.table th {
    text-align: center !important;
}

.font-wallpoet {
    font-family: 'Wallpoet';
}
.font-iceland {
    font-family: 'Iceland';
}
.font-roboto {
    font-family: 'Roboto';
}
.font-light {
    color: #888 !important;
}

.white-hover:hover {
    color: white !important;
    text-decoration: none !important;
}

input.input-uppercase {
    text-transform: uppercase;
}
input.input-uppercase::-webkit-input-placeholder { /* WebKit browsers */
    text-transform: none;
}
input.input-uppercase:-moz-placeholder { /* Mozilla Firefox 4 to 18 */
    text-transform: none;
}
input.input-uppercase::-moz-placeholder { /* Mozilla Firefox 19+ */
    text-transform: none;
}
input.input-uppercase:-ms-input-placeholder { /* Internet Explorer 10+ */
    text-transform: none;
}
input.input-uppercase::placeholder { /* Recent browsers */
    text-transform: none;
}

.highlighted {
    font-weight: bold;
    color: white;
}

.negative {
    color: #ff4495;
}

.input-dark {
    background: rgba(190,190,190,.15) !important;
    border: 0 !important;
    color: rgba(255, 255, 255, 0.6) !important;
    font-weight: 600 !important;
    padding: 8px !important;
    padding-left: 31px !important;
    cursor: pointer !important;
    width: 174px !important;
    height: 36px;
}

.input-dark:focus {
    color: rgba(255, 255, 255, 0.6) !important;
}

.input-dark::placeholder {
    font-weight: normal;
    color: rgba(140,140,140,.75);
}

.input-icon {
    position: absolute;
    margin-left: 5px;
    height: 35px;
    display: flex;
    align-items: center;
    padding-left: 5px;
    color: rgba(140,140,140,.75);
  }

.fs-16 {
    font-size: 16px !important;
}

.btn-danger-slick {
    background: rgba(219, 127, 129, 0.9) !important;
    border: none;
}
.btn-danger-slick:hover {
    background: rgba(219, 127, 129, 1) !important;
}
</style>
