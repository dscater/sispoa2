/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');

window.Vue = require('vue').default;

// ELMENT IO
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import locale from 'element-ui/lib/locale/lang/es'
import { Loading } from 'element-ui';
window.Loading = Loading;
window.Vue.use(ElementUI, { locale })

// BOOSTRAP VUE
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import 'bootstrap-vue/dist/bootstrap-vue.css'
// Make BootstrapVue available throughout your project
Vue.use(BootstrapVue)
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin)

// SWEETALERT2
// ES6 Modules or TypeScript
import Swal from 'sweetalert2'
window.Swal = Swal;

// HIGH CHARTS
var Highcharts = require('highcharts');
require('highcharts/modules/exporting')(Highcharts);
window.Highcharts = Highcharts;

// MOMENT
import moment from 'moment';
moment.locale('es');
Vue.prototype.$moment = moment

// EVENT BUS
export const EventBus = new Vue;
window.EventBus = EventBus;

// COMPONENTES
Vue.component('App', require('./App.vue').default);
Vue.component('Auth', require('./Auth.vue').default);

import router from './routes';
const app = new Vue({
    el: '#app',
    router,
});
