/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
require('./bootstrap');
window.Vue = require('vue');

import BootstrapVue from 'bootstrap-vue';
Vue.use(BootstrapVue);


import App from './App.vue';
import VueAxios from 'vue-axios';
import axios from 'axios';
import store from './store/index.js';

import config from './config/config.js'
import VueConfig from 'vue-config';
Vue.use(VueConfig, config);

Vue.use(VueAxios, axios);

import Vue2Filters from 'vue2-filters'
Vue.use(Vue2Filters);

import money from 'v-money'
Vue.use(money, {precision: 2, prefix: '$ '});

import moment from 'moment'
Vue.prototype.moment = moment

const app = new Vue({
    el: '#app',
    store,
    axios,
    render: h => h(App),
});
