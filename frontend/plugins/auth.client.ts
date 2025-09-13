export default defineNuxtPlugin(() => {
  const authStore = useAuthStore()
  
  // Inicializar autenticação
  authStore.initializeAuth()
  
  // Se tiver token, buscar usuário atualizado
  if (authStore.token && !authStore.user) {
    authStore.fetchUser()
  }
})