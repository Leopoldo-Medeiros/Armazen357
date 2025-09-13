<template>
  <div class="min-h-screen bg-gradient-to-br from-amber-50 to-orange-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <!-- Header -->
      <div class="text-center">
        <h1 class="text-4xl font-bold text-amber-900 mb-2">Armazém 357</h1>
        <p class="text-gray-600">Grãos de Café Premium</p>
        <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
          Entrar na sua conta
        </h2>
      </div>

      <!-- Formulário de Login -->
      <form class="mt-8 space-y-6" @submit.prevent="handleLogin">
        <div class="card">
          <div class="card-body space-y-4">
            <!-- Email -->
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                E-mail
              </label>
              <input
                id="email"
                v-model="form.email"
                type="email"
                required
                class="input-field"
                placeholder="Digite seu e-mail"
                :disabled="authStore.loading"
              />
            </div>

            <!-- Senha -->
            <div>
              <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                Senha
              </label>
              <input
                id="password"
                v-model="form.password"
                type="password"
                required
                class="input-field"
                placeholder="Digite sua senha"
                :disabled="authStore.loading"
              />
            </div>

            <!-- Erro -->
            <div v-if="authStore.error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md">
              {{ authStore.error }}
            </div>

            <!-- Botão de Login -->
            <button
              type="submit"
              class="w-full btn-primary py-3 text-lg"
              :disabled="authStore.loading"
            >
              <span v-if="authStore.loading" class="flex items-center justify-center">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Entrando...
              </span>
              <span v-else>Entrar</span>
            </button>
          </div>
        </div>

        <!-- Links -->
        <div class="text-center space-y-2">
          <p class="text-sm text-gray-600">
            Não tem uma conta?
            <NuxtLink to="/auth/register" class="font-medium text-amber-600 hover:text-amber-500">
              Registre-se aqui
            </NuxtLink>
          </p>
          <p class="text-xs text-gray-500">
            <strong>Demo:</strong> admin@armazem357.com.br / 123456
          </p>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  middleware: 'guest',
  layout: false
})

const authStore = useAuthStore()

const form = reactive({
  email: '',
  password: ''
})

const handleLogin = async () => {
  authStore.clearError()
  
  try {
    await authStore.login(form)
    await navigateTo('/dashboard')
  } catch (error) {
    console.error('Erro no login:', error)
  }
}

// Pré-preencher com dados de demo para desenvolvimento
if (process.dev) {
  form.email = 'admin@armazem357.com.br'
  form.password = '123456'
}
</script>