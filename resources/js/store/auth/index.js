import request from '@/utils/api'
import Cookies from 'js-cookie'
import * as types from '@/store/auth/mutation-types'
import { route } from '@/utils/route'

const namespaced = true

// state
const state = {
  user: null,
  access_token: Cookies.get('access_token'),
  refresh_token: localStorage.getItem('refresh_token'),
  check: localStorage.getItem('refresh_token') !== null,
  authenticated: !!Cookies.get('access_token')
}

// getters
const getters = {
  user: state => state.user,
  access_token: state => state.access_token,
  refresh_token: state => state.refresh_token,
  check: state => state.refresh_token !== null,
  authenticated: state => state.authenticated
}

// mutations
const mutations = {
  [types.LOGIN_SUCCESS](state, { data }) {
    state.access_token = data.access_token
    state.refresh_token = data.refresh_token
    Cookies.set('access_token', data.access_token, { expires: data.expires_in ? data.expires_in : null })
    localStorage.setItem('refresh_token', data.refresh_token)
    localStorage.setItem('access_token', data.refresh_token)
  },

  [types.LOGIN_FAILURE](state) {
    Cookies.remove('access_token')
    localStorage.removeItem('refresh_token')
  },

  [types.LOGOUT](state) {
    state.user = null
    state.access_token = null
    state.refresh_token = null
    Cookies.remove('access_token')
    localStorage.removeItem('refresh_token')
    localStorage.removeItem('access_token')
  },

  [types.FETCH_USER](state, { user }) {
    state.user = user
  },

  [types.UPDATE_TOKEN](state, { token }) {
    state.token = token
  }
}

// actions
const actions = {
  async login({ commit }, payload) {
    try {
      const data = await request.post({ url: route('login'), data: payload })

      commit(types.LOGIN_SUCCESS, data)
    } catch (e) {
      commit(types.LOGIN_FAILURE)
    }
  },

  async logout({ commit }) {
    try {
      await request.post({ url: route('logout') })
    } catch (e) {}

    commit(types.LOGOUT)
  },

  async fetchUserProfile({ commit }) {
    try {
      const data = await request.get({ url: route('profile') })

      commit(types.FETCH_USER, { user: data })
    } catch (e) {}
  },

  async refreshToken({ commit }, payload) {
    try {
      const { data } = await request.post({ url: route('token.refresh'), data: payload })

      commit(types.UPDATE_TOKEN, { token: data.token })
    } catch (e) {

    }
  },

  async register({ commit }, payload) {
    try {
      const data = await request.post({ url: route('register'), data: payload })

      commit(types.LOGIN_SUCCESS, data)
    } catch (e) {

    }
  },

  async emailResetPassword({ commit }, payload) {
    try {
      const response = await request.post({ url: route('password.reset.email'), data: payload })

      return response
    } catch (e) {

    }
  },

  async resetPassword({ commit }, payload) {
    try {
      const { data } = await request.post({ url: route('password.reset'), data: payload })
    } catch (e) {

    }
  }
}

export {
  namespaced,
  state,
  getters,
  mutations,
  actions
}
