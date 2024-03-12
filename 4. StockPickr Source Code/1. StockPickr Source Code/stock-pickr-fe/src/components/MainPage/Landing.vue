<template>
<div>
<div class="home-table font-roboto fw-100">
    <div class="home-table-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="text-center header-content mx-auto">
                        <h1 class="header-title fw-100 text-white mb-0 pt-4">Pick Up The Best Stocks</h1>
                        <p class="header-subtitle pt-4 mx-auto">
                            Boost up your investement's performance by choosing the best stocks possible.
                            We evaluate, score and rank companies for You
                        </p>
                        <div class="header-btn">
                            <router-link :to="
                                {
                                    name: 'main-page',
                                    hash: '#features',
                                }">
                                <button class="btn btn-custom mt-4">Learn More</button>
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="scroll-down">
    <router-link :to="{name: 'main-page', hash: '#features'}" class="scroll">
        <font-awesome-icon icon="chevron-down" class="font-light white-hover" />
    </router-link>
</div>

</div>
</template>

<script>
export default {
    name: 'Landing',
}
</script>

<style lang="scss" scoped>
.home-table {
    display: table;
    width: 100%;
    height: 100%
}
.home-table-center {
    display: table-cell;
    vertical-align: middle
}
.header-content {
    max-width: 700px
}
.header-title {
    margin-top: 140px;
    font-size: 70px;
    line-height: 1.2;
    text-transform: capitalize
}
.header-subtitle {
    font-size: 25px;
    max-width: 600px;
    color: rgba(255, 255, 255, .6)
}
.scroll-down {
    position: absolute;
    bottom: 5vh;
    width: 100%;
    text-align: center;
    color: #fff !important;
    font-size: 50px;
    z-index: 8;
}

.scroll-down a {
    border: none !important;
}

.scroll-down i {
    position: relative;
    -webkit-animation: bounce 2s infinite;
    animation: bounce 2s infinite;
    height: 50px;
    width: 30px;
    line-height: 60px;
    border-radius: 30px;
    border: 2px solid #fff;
    display: inline-block;
    color: #fff;
    font-size: 12px;
}

.scroll-down i:after {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    z-index: -1;
    transition: all .2s
}
</style>
