import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import vueJsx from '@vitejs/plugin-vue-jsx';
import path from 'path';
import ziggy from 'vite-plugin-ziggy';

export default defineConfig({
  plugins: [
    vue(),
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
    }),
    vue(),
    vueJsx(), // ← pastikan versi cocok & posisi setelah vue()
    ziggy(),
  ],
  resolve: {
    alias: { '@': path.resolve(__dirname, './resources/js') },
    dedupe: ['vue', '@vue/runtime-core', '@vue/runtime-dom'],
  },
  server: {
    host: 'localhost',
    port: 5179,
    strictPort: true,

    // URL publik HMR
    // Izinkan front-end di dev.safiradmin.com
   
    hmr: {
      host: 'localhost',
      protocol: 'ws',
    },
  },
})
