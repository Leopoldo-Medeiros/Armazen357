import { defineNuxtConfig } from 'nuxt/config';
import { nodePolyfills } from 'vite-plugin-node-polyfills';

export default defineNuxtConfig({
  vite: {
    plugins: [
      nodePolyfills({
        // To exclude specific polyfills, add them to this list
        exclude: [
          'fs', // Excludes the polyfill for `fs` and `node:fs`.
        ],
        // Whether to polyfill `node:` protocol imports.
        protocolImports: true,
      }),
    ],
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
});
