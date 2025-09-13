<template>
  <div class="min-h-screen bg-gradient-to-br from-amber-50 to-orange-100 flex items-center justify-center">
    <div class="text-center">
      <div class="animate-spin rounded-full h-12 w-12 border-4 border-amber-600 border-t-transparent mx-auto mb-4"></div>
      <p class="text-gray-600">Carregando...</p>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  layout: false
})

const authStore = useAuthStore()

// Redirecionar baseado no estado de autentica\u00e7\u00e3o
onMounted(() => {
  if (authStore.isAuthenticated) {
    navigateTo('/dashboard')
  } else {
    navigateTo('/auth/login')
  }
})
</script>