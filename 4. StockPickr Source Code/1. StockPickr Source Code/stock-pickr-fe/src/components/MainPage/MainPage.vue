<template>
<div>
    <section class="section p-relative default-bg-color h-100vh" id="landing">
        <Landing />
    </section>

    <section class="section bg-white" id="features">
        <Features />
    </section>

    <section class="section light">
        <Cta />
    </section>

    <section class="section bg-white" id="price">
        <Pricing />
    </section>
</div>
</template>

<script lang="ts">
import Landing from '@/components/MainPage/Landing.vue';
import Features from '@/components/MainPage/Features.vue';
import Cta from '@/components/MainPage/Cta.vue';
import Pricing from '@/components/MainPage/Pricing.vue';

export default {
    name: 'MainPage',
    components: {
        Landing,
        Features,
        Cta,
        Pricing
    }
}
</script>

<style lang="scss">
.btn-custom {
    background-color: #2ed078 !important;
    border: 2px solid #2ed078 !important;
    color: #fff !important;
    transition: all .5s !important;
    font-size: 15px !important;
    text-transform: capitalize !important;
}

.p-relative {
    position: relative;
}

.main-title-border::after,
.main-title-border::before {
    display: inline-block;
    margin-top: -7px;
    vertical-align: middle;
    content: "";
    background-color: #d0d0d0;
    width: 30px;
    height: 1px
}

.main-title-border i {
    font-size: 20px
}

.light {
    background-color: #f8f9fa !important;
}
</style>
