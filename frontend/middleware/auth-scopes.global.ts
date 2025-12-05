import { checkScope } from '@/helpers/checkScope'

export default defineNuxtRouteMiddleware((to, from) => {
  if (to.meta.authorize && !checkScope(to.meta.authorize)) {
    return navigateTo('/404')
  }
})
