import Vue from 'vue'
import Router from 'vue-router'
import store from '@/store'

Vue.use(Router)

const DefaultPage = () => import('@/pages/DefaultPage')
const LoginPage = () => import('@/pages/LoginPage')
const RegisterPage = () => import('@/pages/RegisterPage')
const DashboardPage = () => import('@/pages/DashboardPage')

const router = new Router({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'default',
      component: DefaultPage,
      meta: { globalAccess: true },
    },
    {
      path: '/login',
      name: 'login',
      component: LoginPage,
      meta: { globalAccess: true },
    },
    {
      path: '/register',
      name: 'register',
      component: RegisterPage,
      meta: { globalAccess: true },
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: DashboardPage,
    },
  ],
})

router.beforeEach((to, from, next) => {
  const requireAuth = to.matched.some(route => !route.meta.globalAccess)
  const { isLogin } = store.state.Global
  if (!requireAuth || isLogin) {
    return next()
  }

  return next("/login")
})

export default router
