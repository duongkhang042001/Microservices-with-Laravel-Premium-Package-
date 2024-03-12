<template>
<div class="container font-roboto">
    <div class="row">
        <div class="col-lg-12">
            <div class="text-center">
                <h2 class="mb-0 text-capitalize">Best <span class="font-weight-bold">Features</span></h2>
                <div class="main-title-border">
                    <i class="mdi mdi-atom"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4 pt-4">
        <div class="col-lg-4">
            <div class="text-center p-4 mt-3">
                <div class="feature-icon text-danger bg-danger-opacity">
                    <font-awesome-icon icon="balance-scale-left" />
                </div>
                <div class="mt-4">
                    <h5>Financial Statements</h5>
                    <p class="mt-3 text-muted mb-0">Income statements, balance sheets, and cash flows from the past 5 years</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="text-center p-4 mt-3">
                <div class="feature-icon text-green bg-green-opacity">
                    <font-awesome-icon icon="chart-pie" />
                </div>
                <div class="mt-4">
                    <h5>Charts</h5>
                    <p class="mt-3 text-muted mb-0">Everything that you need to visualize a company's performance</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="text-center p-4 mt-3">
                <div class="feature-icon text-blue bg-blue-opacity">
                    <font-awesome-icon icon="chart-line" />
                </div>
                <div class="mt-4">
                    <h5>Analyst Recommendations</h5>
                    <p class="mt-3 text-muted mb-0">Buy, hold, sell recommendations and target prices to help you value a company</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-lg-4">
            <div class="text-center p-4 mt-3">
                <div class="feature-icon text-yellow bg-yellow-opacity">
                    <font-awesome-icon icon="percentage" />
                </div>
                <div class="mt-4">
                    <h5>Metrics</h5>
                    <p class="mt-3 text-muted mb-0">25+ financial metrics in different categories, so you can see where a company is stronger or weaker than it's competitors</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="text-center p-4 mt-3">
                <div class="feature-icon text-purple bg-purple-opacity">
                    <font-awesome-icon icon="star-half-alt" />
                </div>
                <div class="mt-4">
                    <h5>Scores</h5>
                    <p class="mt-3 text-muted mb-0">We score companies based on metrics and performance</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="text-center p-4 mt-3">
                <div class="feature-icon text-dark bg-dark-opacity">
                    <font-awesome-icon icon="less-than-equal" />
                </div>
                <div class="mt-4">
                    <h5>Comparision</h5>
                    <p class="mt-3 text-muted mb-0">You can compare any company with any other company, or a whole sector</p>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    name: 'Features'
}
</script>

<style lang="scss" scoped>
.feature-icon {
    font-size: 30px;
    height: 70px;
    width: 70px;
    position: relative;
    display: inline-block;
    border-radius: 50%;
    padding-left: 36px;
    transition: all .5s
}
h5, h2 {
    color: black;
}
.text-danger {
    color: #e64236!important
}
.text-green {
    color: #33d287!important
}
.text-blue {
    color: #4285f3!important
}
.text-yellow {
    color: #fcbb03!important
}
.text-purple {
    color: #5859fe!important
}
.bg-danger-opacity {
    background-color: rgba(230, 66, 54, .13)
}
.bg-green-opacity {
    background-color: rgba(51, 210, 135, .13)
}
.bg-blue-opacity {
    background-color: rgba(66, 133, 243, .13)
}
.bg-yellow-opacity {
    background-color: rgba(252, 187, 3, .13)
}
.bg-purple-opacity {
    background-color: rgba(88, 89, 254, .13)
}
.bg-dark-opacity {
    background-color: rgba(0, 0, 0, .13)
}
</style>
