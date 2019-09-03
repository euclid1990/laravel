import Vue from 'vue'
import Router from 'vue-router'

import AppLayout from '@/pages/Layout/AppLayout'
import LoginPage from '@/pages/Auth/LoginPage'
import RegisterPage from '@/pages/Auth/RegisterPage'
import DashboardPage from '@/pages/DashboardPage'
import ExportPage from '@/pages/Export'

Vue.use(Router)

const router = new Router({
  mode: 'history',
  routes: [
    {
      path: '/client/vue',
      component: AppLayout,
      meta: { globalAccess: true },
      children: [
        {
          path: 'login',
          name: 'login',
          component: LoginPage,
          meta: { globalAccess: true }
        },
        {
          path: 'register',
          name: 'register',
          component: RegisterPage,
          meta: { globalAccess: true }
        },
        {
          path: 'dashboard',
          name: 'dashboard',
          component: DashboardPage
        },
        {
          path: 'export',
          name: 'export',
          component: ExportPage
        }
      ]
    }
  ]
})

function nextFactory(context, middleware, index) {
  const subsequentMiddleware = middleware[index]
  if (!subsequentMiddleware) return context.next

  return (...parameters) => {
    context.next(...parameters)
    const nextMiddleware = nextFactory(context, middleware, index + 1)
    subsequentMiddleware({ ...context, next: nextMiddleware })
  }
}
// this is before middleware config, middleware default folder is @/router/middleware
// you can pass middle into route to execute pre-route logic you want
// you can use mixin to make same logic as after middleware
router.beforeEach((to, from, next) => {
  if (to.meta.middleware) {
    const middleware = Array.isArray(to.meta.middleware) ? to.meta.middleware : [to.meta.middleware]

    const context = {
      from,
      next,
      router,
      to
    }
    const nextMiddleware = nextFactory(context, middleware, 1)

    return middleware[0]({ ...context, next: nextMiddleware })
  }

  return next()
})

export default router
