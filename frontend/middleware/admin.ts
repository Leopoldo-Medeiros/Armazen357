export default defineNuxtRouteMiddleware((to, from) => {
  const authStore = useAuthStore()
  
  if (!authStore.isAdmin) {
    throw createError({
      statusCode: 403,
      statusMessage: 'Acesso negado. Apenas administradores podem acessar esta área.'
    })
  }
})