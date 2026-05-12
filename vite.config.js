import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import vueJsx from '@vitejs/plugin-vue-jsx';
import path from 'path';
import ziggy from 'vite-plugin-ziggy';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
    }),
    vue(),
    vueJsx(),
    ziggy(),
  ],

  resolve: {
    alias: { '@': path.resolve(__dirname, './resources/js') },
    dedupe: ['vue', '@vue/runtime-core', '@vue/runtime-dom'],
  },

  server: {
    host: '127.0.0.1',
    port: 5179,
    strictPort: true,
    cors: true,
  },
});
