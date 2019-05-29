// import * as AuthAPI from '@/api/AuthAPI'
import { ACCESS_TOKEN } from '@/constants'

const token = localStorage.getItem('ACCESS_TOKEN')
const initialState = () => ({
  isLogin: !!token,
})

const mutations = {
  login(state, payload) {
    state.isLogin = payload
  }
}

const actions = {
  async dispatchLogin({ commit }, data) {
    // const { accessToken } = await AuthAPI.login({ data })
    const { accessToken } = {
        accessToken: 'aaaa'
    }

    localStorage.setItem(ACCESS_TOKEN, accessToken)
    commit('login', true)
  }
}

export default {
  namespaced: true,
  state: initialState(),
  actions,
  mutations,
}
