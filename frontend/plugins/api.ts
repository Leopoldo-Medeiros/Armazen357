export default defineNuxtPlugin(() => {
  const authStore = useAuthStore()
  
  // Interceptar requisições para adicionar token de autenticação
  $fetch.create({
    onRequest({ request, options }) {
      if (authStore.token) {
        const headers = options.headers as Record<string, string> || {}
        headers.Authorization = `Bearer ${authStore.token}`
        options.headers = headers
      }
    },
    
    onResponseError({ response }) {
      // Se receber 401, fazer logout automático
      if (response.status === 401) {
        authStore.logout()
      }
    }
  })
})