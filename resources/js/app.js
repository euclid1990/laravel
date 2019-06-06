
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import 'bootstrap/dist/css/bootstrap.css'
import App from '@/App.vue'
import router from './router'
import VeeValidate from 'vee-validate'
import store from '@/store'

Vue.use(VeeValidate)

new Vue({
  render: h => h(App),
  router,
  store,
}).$mount('#app')
