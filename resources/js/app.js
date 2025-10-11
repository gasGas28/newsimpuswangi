import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { route, ZiggyVue } from 'ziggy-js';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
import 'bootstrap';
import 'tailwindcss';
import axios from 'axios';

// Arahkan axios ke backend Laravel
axios.defaults.baseURL = 'http://127.0.0.1:8000';

// Kirim cookie session/XSRF
axios.defaults.withCredentials = true;

// Header standar AJAX
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// (Jika TIDAK pakai Sanctum) ambil token dari <meta name="csrf-token">
const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if (token) {
  axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
}

// (Opsional) export ke window biar gampang dipakai di komponen
window.axios = axios;

createInertiaApp({
  // title: (title) => `${title} - ${appName}`,
  resolve: (name) =>
    resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
  setup({ el, App, props, plugin }) {
    return createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .mount(el);
  },
  progress: {
    color: '#4B5563',
  },
});
