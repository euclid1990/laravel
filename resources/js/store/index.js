import Vuex from 'vuex'
import Vue from 'vue'
import * as auth from '@/store/auth'

Vue.use(Vuex)

const store = new Vuex.Store({
  modules: {
    auth
  }
})

export default store
