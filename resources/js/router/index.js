import Vue from 'vue'
import Router from 'vue-router'
import store from '@/store'

Vue.use(Router)

import AppLayout from '@/pages/Layout/AppLayout'
import LoginPage from '@/pages/Auth/LoginPage'
import RegisterPage from '@/pages/Auth/RegisterPage'
import DashboardPage from '@/pages/DashboardPage'

const router = new Router({
  mode: 'history',
  routes: [
    {
      path: '/client/vue',
      name: 'app',
      component: AppLayout,
      meta: { globalAccess: true },
      children: [
        {
          path: 'login',
          name: 'login',
          component: LoginPage,
          meta: { globalAccess: true },
        },
        {
          path: 'register',
          name: 'register',
          component: RegisterPage,
          meta: { globalAccess: true },
        },
        {
          path: 'dashboard',
          name: 'dashboard',
          component: DashboardPage,
        },
      ],
    },
  ],
})

router.beforeEach((to, from, next) => {
  const requireAuth = to.matched.some(route => !route.meta.globalAccess)
  const { isLogin } = store.state.Global

  if (isLogin || !requireAuth) {
    return next()
  }

  return next('/client/vue/login')
})

export default router
