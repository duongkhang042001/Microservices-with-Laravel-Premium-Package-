<template>
<div class="container font-roboto fw-100">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="text-center">
                <h2>Are You Ready To Join?</h2>
                <p class="text-muted mx-auto cta-desc mt-4 pt-2">
                    Boost up your investement's performance by picking the best stocks possible, ranked by the most important 25+ financial metrics
                </p>
                <div class="mt-4 pt-3">
                    <router-link :to="{name: 'signUp'}">
                        <button type="button" class="btn btn-custom btn-rounded">Start Free Account</button>
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    name: 'Cta'
}
</script>

<style lang="scss" scoped>
.text-muted {
    color: #222 !important;
}
.cta-desc {
    max-width: 635px;
    font-size: 18px;
}
.btn-rounded {
    border-radius: 30px
}
h2 {
    color: black;
}
</style>
