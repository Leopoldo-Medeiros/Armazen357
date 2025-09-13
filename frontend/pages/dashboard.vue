<template>
  <div>
    <!-- Page Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard</h1>
      <p class="text-gray-600">Bem-vindo ao Armazém 357, {{ authStore.user?.name }}!</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div class="card">
        <div class="card-body">
          <div class="flex items-center">
            <div class="p-2 bg-blue-100 rounded-lg">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Total de Pedidos</p>
              <p class="text-2xl font-bold text-gray-900">{{ dashboardData.total_orders }}</p>
            </div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-body">
          <div class="flex items-center">
            <div class="p-2 bg-green-100 rounded-lg">
              <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Produtos</p>
              <p class="text-2xl font-bold text-gray-900">{{ dashboardData.total_products }}</p>
            </div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-body">
          <div class="flex items-center">
            <div class="p-2 bg-yellow-100 rounded-lg">
              <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Clientes</p>
              <p class="text-2xl font-bold text-gray-900">{{ dashboardData.total_customers }}</p>
            </div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-body">
          <div class="flex items-center">
            <div class="p-2 bg-amber-100 rounded-lg">
              <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Receita</p>
              <p class="text-2xl font-bold text-gray-900">R$ {{ formatCurrency(dashboardData.revenue) }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- User Info Card -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <div class="card">
        <div class="card-header">
          <h3 class="text-lg font-medium text-gray-900">Informações da Conta</h3>
        </div>
        <div class="card-body">
          <dl class="space-y-3">
            <div>
              <dt class="text-sm font-medium text-gray-500">Nome</dt>
              <dd class="text-sm text-gray-900">{{ authStore.user?.name }}</dd>
            </div>
            <div>
              <dt class="text-sm font-medium text-gray-500">E-mail</dt>
              <dd class="text-sm text-gray-900">{{ authStore.user?.email }}</dd>
            </div>
            <div>
              <dt class="text-sm font-medium text-gray-500">Roles</dt>
              <dd class="text-sm text-gray-900">
                <div class="flex flex-wrap gap-1 mt-1">
                  <span 
                    v-for="role in authStore.user?.roles" 
                    :key="role"
                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800"
                  >
                    {{ role }}
                  </span>
                </div>
              </dd>
            </div>
            <div v-if="authStore.user?.last_login_at">
              <dt class="text-sm font-medium text-gray-500">Último Login</dt>
              <dd class="text-sm text-gray-900">{{ formatDate(authStore.user.last_login_at) }}</dd>
            </div>
          </dl>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <h3 class="text-lg font-medium text-gray-900">Permissões</h3>
        </div>
        <div class="card-body">
          <div class="space-y-2 max-h-64 overflow-y-auto">
            <div 
              v-for="permission in authStore.user?.permissions" 
              :key="permission"
              class="flex items-center justify-between py-1"
            >
              <span class="text-sm text-gray-700">{{ permission }}</span>
              <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
              </svg>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  middleware: 'auth'
})

const authStore = useAuthStore()

// Dashboard data - normalmente viria de uma API
const dashboardData = reactive({
  total_orders: 0,
  total_products: 0,
  total_customers: 0,
  revenue: 0
})

// Formatação de moeda
const formatCurrency = (value: number) => {
  return new Intl.NumberFormat('pt-BR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  }).format(value)
}

// Formatação de data
const formatDate = (dateString: string) => {
  return new Intl.DateTimeFormat('pt-BR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  }).format(new Date(dateString))
}

// Carregar dados do dashboard se o usuário tiver permissão
if (authStore.hasPermission('view_dashboard')) {
  try {
    // Simulação de dados - em produção, fazer requisição à API
    dashboardData.total_orders = 127
    dashboardData.total_products = 45
    dashboardData.total_customers = 89
    dashboardData.revenue = 25340.50
  } catch (error) {
    console.error('Erro ao carregar dados do dashboard:', error)
  }
}
</script>