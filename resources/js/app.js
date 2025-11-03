import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { route, ZiggyVue } from 'ziggy-js';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
import 'bootstrap';
import 'tailwindcss';
import axios from 'axios';
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

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
// =================== AUTO LOGOUT + TIMER DISPLAY ===================
document.addEventListener("DOMContentLoaded", () => {
  const maxIdle = 600 * 10000; // 60 detik
  const warningBefore = 10 * 1000; // popup muncul 10 detik sebelum logout
  const events = ["mousemove", "keypress", "click", "scroll", "touchstart"];
  let timeLeft = maxIdle / 1000;
  let timerInterval;
  let warningShown = false;

  // === buat elemen floating timer ===
  const timerDiv = document.createElement("div");
  timerDiv.style.position = "fixed";
  timerDiv.style.bottom = "15px";
  timerDiv.style.right = "15px";
  timerDiv.style.background = "rgba(0,0,0,0.7)";
  timerDiv.style.color = "white";
  timerDiv.style.padding = "8px 14px";
  timerDiv.style.borderRadius = "8px";
  timerDiv.style.fontFamily = "monospace";
  timerDiv.style.zIndex = "9999";
  timerDiv.textContent = `Auto logout: ${timeLeft}s`;
  document.body.appendChild(timerDiv);

  const resetTimer = () => {
    timeLeft = maxIdle / 1000;
    warningShown = false;
  };

  const autoLogout = async () => {
    clearInterval(timerInterval);
    try {
      await axios.post("/logout");
      window.location.href = "/login";
    } catch (error) {
      console.error("Auto logout gagal:", error);
    }
  };

  const showWarning = () => {
    warningShown = true;
    Swal.fire({
      title: "Kamu akan logout otomatis!",
      text: "Tidak ada aktivitas. Logout dalam 10 detik...",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Lanjutkan sesi",
      cancelButtonText: "Logout sekarang",
      timer: warningBefore,
      timerProgressBar: true,
      allowOutsideClick: false,
      allowEscapeKey: false,
    }).then((result) => {
      if (result.isConfirmed) {
        resetTimer();
      } else {
        autoLogout();
      }
    });

    // otomatis logout setelah waktu habis
    setTimeout(() => {
      if (warningShown) autoLogout();
    }, warningBefore);
  };

  // === hitung mundur setiap detik ===
  timerInterval = setInterval(() => {
    timeLeft--;
    timerDiv.textContent = `Auto logout: ${timeLeft}s`;

    if (timeLeft <= warningBefore / 1000 && !warningShown) {
      showWarning();
    }
    if (timeLeft <= 0) {
      autoLogout();
    }
  }, 1000);

  // reset timer tiap ada aktivitas
  events.forEach((e) => {
    window.addEventListener(e, () => resetTimer());
  });
});


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
