import Vue from 'vue'
import Router from 'vue-router'

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
    },
    {
      path: '/login',
      name: 'login',
      component: LoginPage,
    },
    {
      path: '/register',
      name: 'register',
      component: RegisterPage,
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: DashboardPage,
    },
  ],
})

export default router
