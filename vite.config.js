import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import vueJsx from '@vitejs/plugin-vue-jsx'
import path from 'path';
import ziggy from 'vite-plugin-ziggy';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
    }),
    vue(),
    vueJsx(),       // ← pastikan versi cocok & posisi setelah vue()
    ziggy(),

  ],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './resources/js'),
    },
  },

server: {
  host: true,
  port: 5173,
  strictPort: true,
  hmr: {
    host: 'dev-hmr.safiradmin.com', // <— ganti
    protocol: 'wss',
    clientPort: 443, // <— BUKAN 'port'
    // jangan set 'port' di sini
  },
}


});
