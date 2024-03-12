<template>
<div class="container font-roboto">
    <div class="row">
        <div class="col-lg-12">
            <div class="text-center">
                <h2 class="mb-0 text-capitalize">Our <span class="font-weight-bold">Pricing</span></h2>
                <div class="main-title-border"></div>
            </div>
        </div>
    </div>
    <div class="row mt-4 pt-4">
        <div class="col-lg-4"></div>
        <div class="col-lg-4">
            <div class="mt-3 bg-white text-center price-order-box position-relative">
                <div class="price-name">
                    <h5 class="text-muted mb-0">Free For Limited Time</h5>
                </div>
                <div class="plan-price mt-4">
                    <h1 class="mb-0"><sup>$</sup>0.00</h1>
                </div>
                <div class="plan-price-features fs-16">
                    <p>Compare <span class="font-weight-bold text-dark">2000+</span> companies</p>
                    <p>No credit card needed</p>
                    <p>Unlimited use</p>
                </div>
                <div>
                    <router-link :to="{name: 'signUp'}">
                        <button type="button" class="btn btn-custom">Sign Up</button>
                    </router-link>
                </div>
            </div>
        </div>
        <div class="col-lg-4"></div>
    </div>
</div>
</template>

<script>
export default {
    name: 'Pricing'
}
</script>

<style lang="scss" scoped>
h2 {
    color: black;
}
.price-order-box {
    padding: 40px;
    border: 1px solid #e9e9e9;
    transition: all .5s;
    border-top: 3px solid #2ed078;

    .lable {
        background: #2ed078;
        padding: 8px 0;
        text-align: center;
        width: 130px;
        position: absolute;
        top: -17px;
        right: 0;
        left: 0;
        border-radius: 6px;

        h6 {
            color: #fff;
            font-size: 14px
        }
    }

    .plan-price {
        h1 {
            color: black;
            font-size: 49px;
            font-weight: 600;

            sup {
                position: relative;
                top: -15px
            }

            sup, sub {
                font-size: 26px
            }
        }
    }

    .price-name {
        h5 {
            font-weight: 600;
            font-size: 20px;
        }
    }

    .plan-price-features {
        padding: 40px 0;

        p {
            font-size: 16px;
            color: #777
        }
    }
}
</style>
