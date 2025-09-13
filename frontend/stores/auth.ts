import { defineStore } from 'pinia'

export interface User {
  id: number
  name: string
  email: string
  phone?: string
  document?: string
  document_type?: 'cpf' | 'cnpj'
  address?: string
  city?: string
  state?: string
  zipcode?: string
  is_active: boolean
  last_login_at?: string
  roles: string[]
  permissions: string[]
}

export interface LoginCredentials {
  email: string
  password: string
}

export interface RegisterData {
  name: string
  email: string
  password: string
  password_confirmation: string
  phone?: string
  document?: string
  document_type?: 'cpf' | 'cnpj'
  address?: string
  city?: string
  state?: string
  zipcode?: string
  customer_type?: 'b2c' | 'b2b'
}

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null as User | null,
    token: null as string | null,
    loading: false,
    error: null as string | null
  }),

  getters: {
    isAuthenticated: (state) => !!state.token && !!state.user,
    isAdmin: (state) => state.user?.roles?.includes('admin') || state.user?.roles?.includes('super_admin') || false,
    isSuperAdmin: (state) => state.user?.roles?.includes('super_admin') || false,
    isCustomer: (state) => state.user?.roles?.some(role => ['customer_b2c', 'customer_b2b'].includes(role)) || false,
    isB2B: (state) => state.user?.roles?.includes('customer_b2b') || false,
    hasPermission: (state) => (permission: string) => state.user?.permissions?.includes(permission) || false,
    hasRole: (state) => (role: string) => state.user?.roles?.includes(role) || false,
    hasAnyRole: (state) => (roles: string[]) => roles.some(role => state.user?.roles?.includes(role))
  },

  actions: {
    async login(credentials: LoginCredentials) {
      this.loading = true
      this.error = null
      
      try {
        const { data } = await $fetch<{
          message: string
          access_token: string
          token_type: string
          user: User
        }>('/auth/login', {
          method: 'POST',
          body: credentials,
          baseURL: useRuntimeConfig().public.apiBase
        })

        this.token = data.access_token
        this.user = data.user
        
        // Salvar no localStorage
        if (process.client) {
          localStorage.setItem('auth.token', data.access_token)
          localStorage.setItem('auth.user', JSON.stringify(data.user))
        }

        // Configurar cookie para SSR
        const tokenCookie = useCookie('auth.token', {
          default: () => null,
          secure: true,
          sameSite: 'strict',
          maxAge: 60 * 60 * 24 * 7 // 7 dias
        })
        tokenCookie.value = data.access_token

        return data
      } catch (error: any) {
        this.error = error?.data?.message || 'Erro ao fazer login'
        throw error
      } finally {
        this.loading = false
      }
    },

    async register(registerData: RegisterData) {
      this.loading = true
      this.error = null
      
      try {
        const { data } = await $fetch<{
          message: string
          access_token: string
          token_type: string
          user: User
        }>('/auth/register', {
          method: 'POST',
          body: registerData,
          baseURL: useRuntimeConfig().public.apiBase
        })

        this.token = data.access_token
        this.user = data.user
        
        // Salvar no localStorage
        if (process.client) {
          localStorage.setItem('auth.token', data.access_token)
          localStorage.setItem('auth.user', JSON.stringify(data.user))
        }

        // Configurar cookie para SSR
        const tokenCookie = useCookie('auth.token')
        tokenCookie.value = data.access_token

        return data
      } catch (error: any) {
        this.error = error?.data?.message || 'Erro ao registrar'
        throw error
      } finally {
        this.loading = false
      }
    },

    async logout() {
      try {
        if (this.token) {
          await $fetch('/auth/logout', {
            method: 'POST',
            headers: {
              'Authorization': `Bearer ${this.token}`
            },
            baseURL: useRuntimeConfig().public.apiBase
          })
        }
      } catch (error) {
        console.error('Erro ao fazer logout no servidor:', error)
      } finally {
        // Limpar estado local independentemente do resultado da API
        this.user = null
        this.token = null
        this.error = null
        
        // Limpar localStorage
        if (process.client) {
          localStorage.removeItem('auth.token')
          localStorage.removeItem('auth.user')
        }

        // Limpar cookie
        const tokenCookie = useCookie('auth.token')
        tokenCookie.value = null

        // Redirecionar para login
        await navigateTo('/auth/login')
      }
    },

    async fetchUser() {
      if (!this.token) return
      
      try {
        const { user } = await $fetch<{ user: User }>('/auth/me', {
          headers: {
            'Authorization': `Bearer ${this.token}`
          },
          baseURL: useRuntimeConfig().public.apiBase
        })
        
        this.user = user
        
        if (process.client) {
          localStorage.setItem('auth.user', JSON.stringify(user))
        }
      } catch (error) {
        console.error('Erro ao buscar usuário:', error)
        await this.logout()
      }
    },

    async updateProfile(profileData: Partial<User>) {
      this.loading = true
      this.error = null
      
      try {
        const { user } = await $fetch<{ message: string; user: User }>('/auth/profile', {
          method: 'PUT',
          body: profileData,
          headers: {
            'Authorization': `Bearer ${this.token}`
          },
          baseURL: useRuntimeConfig().public.apiBase
        })
        
        this.user = { ...this.user, ...user }
        
        if (process.client) {
          localStorage.setItem('auth.user', JSON.stringify(this.user))
        }
        
        return user
      } catch (error: any) {
        this.error = error?.data?.message || 'Erro ao atualizar perfil'
        throw error
      } finally {
        this.loading = false
      }
    },

    initializeAuth() {
      // Recuperar do localStorage se disponível
      if (process.client) {
        const savedToken = localStorage.getItem('auth.token')
        const savedUser = localStorage.getItem('auth.user')
        
        if (savedToken && savedUser) {
          this.token = savedToken
          this.user = JSON.parse(savedUser)
        }
      }
      
      // Recuperar do cookie (para SSR)
      const tokenCookie = useCookie('auth.token')
      if (tokenCookie.value && !this.token) {
        this.token = tokenCookie.value
      }
    },

    clearError() {
      this.error = null
    }
  }
})