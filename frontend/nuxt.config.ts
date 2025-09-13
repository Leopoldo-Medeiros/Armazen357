// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
  
  // Runtime Config
  runtimeConfig: {
    // Private keys (only available on server-side)
    apiSecret: '123',
    // Public keys (exposed to client-side)
    public: {
      apiBase: 'http://127.0.0.1:8000/api',
      appName: 'Armazém 357',
      siteUrl: 'http://localhost:3000'
    }
  },

  // CSS Framework
  css: ['~/assets/css/main.css'],

  vite: {
    define: {
      'process.env': {}
    },
    resolve: {
      alias: {
        crypto: 'crypto-browserify',
        stream: 'stream-browserify',
      },
    },
    optimizeDeps: {
      esbuildOptions: {
        // Node.js global to browser globalThis
        define: {
          global: 'globalThis'
        },
      },
    },
  },
  
  build: {
    transpile: ['@vuepic/vue-datepicker']
  },
  
  modules: [
    '@nuxtjs/tailwindcss',
    '@pinia/nuxt'
  ],

  // App configuration
  app: {
    head: {
      title: 'Armazém 357 - Grãos de Café Premium',
      meta: [
        { charset: 'utf-8' },
        { name: 'viewport', content: 'width=device-width, initial-scale=1' },
        { name: 'description', content: 'Armazém 357 - A melhor seleção de grãos de café do Brasil para atacado e varejo' }
      ],
      link: [
        { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }
      ]
    }
  }
})
