<script setup>
import { computed } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  status: {
    type: Number,
    required: true,
  },
  message: {
    type: String,
    default: null,
  },
})

const content = computed(() => {
  const map = {
    403: {
      title: 'Akses Ditolak',
      text: 'Anda tidak memiliki akses ke halaman ini.',
      icon: 'lock',
    },
    404: {
      title: 'Halaman Tidak Ditemukan',
      text: 'Halaman yang Anda cari tidak tersedia atau sudah dipindahkan.',
      icon: 'search',
    },
    419: {
      title: 'Sesi Kedaluwarsa',
      text: 'Sesi Anda telah habis. Silakan muat ulang halaman dan login kembali.',
      icon: 'clock',
    },
    500: {
      title: 'Terjadi Kesalahan Server',
      text: 'Terjadi kesalahan pada server. Tim teknis sudah diberi tahu.',
      icon: 'alert',
    },
    503: {
      title: 'Sedang Perawatan',
      text: 'Aplikasi sedang dalam perbaikan. Silakan coba beberapa saat lagi.',
      icon: 'tool',
    },
  }
  return map[props.status] ?? {
    title: 'Terjadi Kesalahan',
    text: 'Maaf, ada yang tidak beres.',
    icon: 'alert',
  }
})

function goBack() {
  // Kembali ke halaman sebelumnya kalau memungkinkan, kalau tidak ke dashboard
  if (window.history.length > 1) {
    window.history.back()
  } else {
    router.visit('/dashboard')
  }
}

function goDashboard() {
  router.visit('/dashboard')
}
</script>

<template>
  <div class="error-overlay">
    <div class="error-card">
      <div class="error-icon" :class="`icon-${content.icon}`">
        <!-- lock -->
        <svg v-if="content.icon === 'lock'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <rect x="4" y="10" width="16" height="10" rx="2" />
          <path d="M8 10V7a4 4 0 0 1 8 0v3" />
        </svg>
        <!-- search -->
        <svg v-else-if="content.icon === 'search'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="11" cy="11" r="7" />
          <path d="M21 21l-4.3-4.3" />
        </svg>
        <!-- clock -->
        <svg v-else-if="content.icon === 'clock'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="9" />
          <path d="M12 7v5l3 3" />
        </svg>
        <!-- tool -->
        <svg v-else-if="content.icon === 'tool'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M14.7 6.3a4 4 0 0 1-5.4 5.4L4 17l3 3 5.3-5.3a4 4 0 0 1 5.4-5.4l-3-3z" />
        </svg>
        <!-- alert (default) -->
        <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="9" />
          <path d="M12 8v5" />
          <circle cx="12" cy="16" r="0.5" fill="currentColor" />
        </svg>
      </div>

      <div class="error-status">{{ status }}</div>
      <h2 class="error-title">{{ content.title }}</h2>
      <p class="error-text">{{ message || content.text }}</p>

      <div class="error-actions">
        <button class="btn btn-secondary" @click="goBack">Kembali</button>
        <button class="btn btn-primary" @click="goDashboard">Ke Dashboard</button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.error-overlay {
  position: fixed;
  inset: 0;
  background: rgba(15, 23, 20, 0.45);
  backdrop-filter: blur(2px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
  padding: 16px;
}

.error-card {
  background: #ffffff;
  border-radius: 16px;
  padding: 36px 32px 28px;
  text-align: center;
  max-width: 380px;
  width: 100%;
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25);
  animation: pop-in 0.18s ease-out;
}

@keyframes pop-in {
  from {
    opacity: 0;
    transform: scale(0.96) translateY(6px);
  }
  to {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}

.error-icon {
  width: 56px;
  height: 56px;
  margin: 0 auto 16px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #fdecea;
  color: #d64545;
}

.error-icon svg {
  width: 28px;
  height: 28px;
}

.error-status {
  font-size: 13px;
  font-weight: 700;
  letter-spacing: 0.06em;
  color: #9aa5a0;
  margin-bottom: 4px;
}

.error-title {
  font-size: 20px;
  font-weight: 700;
  color: #16211c;
  margin: 0 0 8px;
}

.error-text {
  font-size: 14px;
  line-height: 1.5;
  color: #5b6a63;
  margin: 0 0 24px;
}

.error-actions {
  display: flex;
  gap: 10px;
  justify-content: center;
}

.btn {
  border: none;
  border-radius: 8px;
  padding: 10px 18px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: opacity 0.15s ease, transform 0.1s ease;
}

.btn:active {
  transform: scale(0.97);
}

.btn-primary {
  background: #16a36f;
  color: #ffffff;
}

.btn-primary:hover {
  opacity: 0.92;
}

.btn-secondary {
  background: #f1f4f2;
  color: #16211c;
}

.btn-secondary:hover {
  background: #e6eae7;
}
</style>
