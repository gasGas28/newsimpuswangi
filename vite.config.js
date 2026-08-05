import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import vueJsx from '@vitejs/plugin-vue-jsx';
import path from 'path';
import ziggy from 'vite-plugin-ziggy';
import { fileURLToPath, URL } from 'node:url';

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

  test: {
    environment: 'jsdom',
    globals: true, // supaya describe/it/expect tidak perlu di-import manual
    include: ['resources/js/**/*.spec.js', 'resources/js/**/*.test.js'],
    coverage: {
      provider: 'v8',
      reporter: ['text', 'html'],
      exclude: ['**/*.spec.js', '**/*.test.js'],
    },
  },
});
