
require('./bootstrap');

import App from './App.vue';
import Vue from 'vue';
import VueRouter from 'vue-router';
import router from './router';
import ViewUI from 'view-design';
import 'view-design/dist/styles/iview.css';

// Set Vue globally
window.Vue = Vue

// Set Vue router
Vue.router = router
Vue.use(VueRouter)

// Set ViewUI
Vue.use(ViewUI)

// Load App
Vue.component('app', App)

const app = new Vue({
    el: '#app',
    router: router,
    render: h=>h(App)
});

