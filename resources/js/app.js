
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import App from '@/App.vue'
import router from '@/router'

import { i18n } from '@/utils'
import store from '@/store'

import BootstrapVue from 'bootstrap-vue'
import VeeValidate from 'vee-validate'
import VueSweetalert2 from 'vue-sweetalert2'
import Fragment from 'vue-fragment'
import Vue from 'vue'

window.endpoint = require('./endpoint')

Vue.use(Fragment.Plugin)
Vue.use(BootstrapVue)
Vue.use(VueSweetalert2)
Vue.use(VeeValidate)

new Vue({
  render: h => h(App),
  i18n,
  router,
  store
}).$mount('#app')
