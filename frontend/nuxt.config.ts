// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  $development: undefined, $env: undefined, $meta: undefined, $production: undefined, $test: undefined,
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
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
    '@nuxtjs/tailwindcss'
  ]
})
