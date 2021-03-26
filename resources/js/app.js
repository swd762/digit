
require('./bootstrap');

import 'es6-promise/auto'
import App from './App.vue';
import Vue from 'vue';
import VueRouter from 'vue-router';
// Компонент для аутентификации и авторизации пользователй
import VueAuth from '@websanova/vue-auth'

import VueAxios from 'vue-axios'
import router from './router';
import auth from './auth'

// Компонент viewUI для компонентов интерфейса
import ViewUI from 'view-design';
import locale from 'view-design/dist/locale/ru-RU';
import 'view-design/dist/styles/iview.css';


window.moment = require('moment-timezone');

// Устанавливаем Vue глобально
window.Vue = Vue

// Устанавливаем Vue Router
Vue.router = router
Vue.use(VueRouter)

// Устанавливаем ViewUI
Vue.use(ViewUI, { locale })

// Устанавливаем Vue authentication
Vue.use(VueAxios, axios)
// базовый маршрут для запроса по api
axios.defaults.baseURL = `${process.env.MIX_APP_URL}/api`
Vue.use(VueAuth, auth)

// Загружаем Vue App
Vue.component('app', App)

const app = new Vue({
    // el: '#app',
    router: router,
    // render: h=>h(App)
}).$mount('#app');

