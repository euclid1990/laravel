import Vuex from 'vuex'
import Vue from 'vue'
import Global from '@/store/modules/Global'

Vue.use(Vuex)

const store = new Vuex.Store({
  modules: {
    Global
  }
})

export default store
