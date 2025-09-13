<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header/Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
      <div class="container-fluid">
        <div class="flex justify-between items-center py-4">
          <!-- Logo -->
          <div class="flex items-center space-x-4">
            <NuxtLink to="/dashboard" class="text-2xl font-bold text-amber-800">
              Armazém 357
            </NuxtLink>
            <span class="text-sm text-gray-500 hidden sm:block">Grãos de Café Premium</span>
          </div>

          <!-- User Menu -->
          <div class="flex items-center space-x-4">
            <span class="text-sm text-gray-600 hidden sm:block">
              Olá, {{ authStore.user?.name }}
            </span>
            
            <!-- Roles Badge -->
            <div class="flex space-x-1">
              <span 
                v-for="role in authStore.user?.roles" 
                :key="role"
                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                :class="getRoleBadgeClass(role)"
              >
                {{ getRoleDisplayName(role) }}
              </span>
            </div>

            <!-- Logout Button -->
            <button 
              @click="handleLogout"
              class="btn-secondary text-sm py-2 px-3"
            >
              Sair
            </button>
          </div>
        </div>
      </div>
    </nav>

    <!-- Sidebar + Content -->
    <div class="flex">
      <!-- Sidebar -->
      <aside class="w-64 bg-white shadow-sm min-h-screen">
        <nav class="p-4 space-y-2">
          <!-- Dashboard -->
          <NuxtLink 
            to="/dashboard"
            class="flex items-center space-x-3 px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100"
            :class="{ 'bg-amber-100 text-amber-800': $route.path === '/dashboard' }"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2V7z"></path>
            </svg>
            <span>Dashboard</span>
          </NuxtLink>

          <!-- Admin Menu -->
          <div v-if="authStore.isAdmin">
            <h3 class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
              Administração
            </h3>
            <NuxtLink 
              to="/admin/users"
              class="flex items-center space-x-3 px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-6.5a4 4 0 11-5.292 0M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"></path>
              </svg>
              <span>Usuários</span>
            </NuxtLink>
          </div>

          <!-- Store Menu -->
          <div v-if="authStore.hasPermission('browse_store')">
            <h3 class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
              Loja
            </h3>
            <NuxtLink 
              to="/store"
              class="flex items-center space-x-3 px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
              </svg>
              <span>Produtos</span>
            </NuxtLink>
          </div>
        </nav>
      </aside>

      <!-- Main Content -->
      <main class="flex-1 p-6">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
const authStore = useAuthStore()

const handleLogout = async () => {
  await authStore.logout()
}

const getRoleBadgeClass = (role: string) => {
  switch (role) {
    case 'super_admin':
      return 'bg-red-100 text-red-800'
    case 'admin':
      return 'bg-blue-100 text-blue-800'
    case 'staff':
      return 'bg-green-100 text-green-800'
    case 'customer_b2b':
      return 'bg-purple-100 text-purple-800'
    case 'customer_b2c':
      return 'bg-gray-100 text-gray-800'
    default:
      return 'bg-gray-100 text-gray-800'
  }
}

const getRoleDisplayName = (role: string) => {
  switch (role) {
    case 'super_admin': return 'Super Admin'
    case 'admin': return 'Admin'
    case 'staff': return 'Funcionário'
    case 'customer_b2b': return 'B2B'
    case 'customer_b2c': return 'B2C'
    default: return role
  }
}
</script>