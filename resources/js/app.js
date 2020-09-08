
require('./bootstrap');

import 'es6-promise/auto'
import App from './App.vue';
import Vue from 'vue';
import VueRouter from 'vue-router';
import VueAuth from '@websanova/vue-auth'
import VueAxios from 'vue-axios'
import router from './router';
import auth from './auth'
import ViewUI from 'view-design';
import 'view-design/dist/styles/iview.css';


// Set Vue globally
window.Vue = Vue

// Set Vue router
Vue.router = router
Vue.use(VueRouter)

// Set ViewUI
Vue.use(ViewUI)

// Set Vue authentication
Vue.use(VueAxios, axios)
axios.defaults.baseURL = `${process.env.MIX_APP_URL}/api`
Vue.use(VueAuth, auth)

// Load App
Vue.component('app', App)

const app = new Vue({
    el: '#app',
    router: router,
    render: h=>h(App)
});

