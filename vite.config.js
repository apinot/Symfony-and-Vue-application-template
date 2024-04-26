import { fileURLToPath, URL } from 'node:url'
import { dirname, resolve } from 'node:path'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import VueI18nPlugin from '@intlify/unplugin-vue-i18n/vite'

// https://vitejs.dev/config/
export default defineConfig({
  root: './assets',
  base: '/assets/',
  build: {
      manifest: true,
      assetsDir: '',
      outDir: '../public/assets',
      rollupOptions: {
          output: {
              manualChunks: undefined, // On ne veut pas créer un fichier vendors, car on n'a ici qu'un point d'entré
          },
          input: {
              'main.js': './assets/main.js'
          }
      },
      emptyOutDir: true,
  },
  plugins: [
    vue(),
      VueI18nPlugin({
          include: resolve(dirname(fileURLToPath(import.meta.url)), './assets/locales/**'),
          jitCompilation: false,
      }),
  ],
});
