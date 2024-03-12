import { createApp } from 'vue';
import App from './App.vue';
import './registerServiceWorker';
import router from './router';
import store from './store/Store';
import config from './app.config';
import axios from 'axios';
import toastr from 'toastr';
import 'chartjs-plugin-colorschemes';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faBalanceScaleLeft, faChartPie, faChartLine, faStarHalfAlt, faLessThanEqual, faPercentage, faChevronDown } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

library
    .add(faBalanceScaleLeft, faChartPie, faChartLine, faStarHalfAlt, faLessThanEqual, faPercentage, faChevronDown);

createApp(App)
    .use(router)
    .use(store)
    .component('font-awesome-icon', FontAwesomeIcon)
    .mount('#app');

axios.defaults.baseURL = config.baseUrl;

toastr.options = {
    "closeButton": false,
    "debug": false,
    "newestOnTop": false,
    "progressBar": true,
    "positionClass": "toast-bottom-center",
    "preventDuplicates": false,
    "onclick": undefined,
    "showDuration": 300,
    "hideDuration": 1000,
    "timeOut": 2000,
    "extendedTimeOut": 1000,
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
  }
