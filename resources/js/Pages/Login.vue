<template>
  <div class="flex min-h-screen items-center justify-center bg-gray-100">
    <div class="w-full max-w-md bg-white p-6 rounded shadow">
      <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>

      <form @submit.prevent="submit">
        <div class="mb-4">
          <label class="block text-gray-700 mb-1">Email</label>
          <input type="email" v-model="form.email" class="w-full p-2 border rounded" required />
        </div>

        <div class="mb-6">
          <label class="block text-gray-700 mb-1">Password</label>
          <input type="password" v-model="form.password" class="w-full p-2 border rounded" required />
        </div>

        <!-- Tambahkan reCAPTCHA widget di sini -->
        <div class="mb-4">
          <div class="g-recaptcha" data-sitekey="6LcUtIQrAAAAACy8jMEsJqDcJYipQSw5hORlhzeH"></div>
        </div>

        <button type="submit"
          class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
          Login
        </button>
      </form>

      <p v-if="errors" class="text-red-600 mt-4">{{ errors }}</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'

const form = ref({
  email: '',
  password: ''
})

const errors = ref(null)

// Supaya widget muncul walau pindah-pindah halaman Inertia
onMounted(() => {
  if (window.grecaptcha && document.querySelector('.g-recaptcha')) {
    window.grecaptcha.render(document.querySelector('.g-recaptcha'), {
      sitekey: '6LdYuYQrAAAAALYV7vXe8Oz3GuHuRGMfCtnyn9rb'
    })
  }
})

function submit() {
  errors.value = null

  // Cek token dari widget
  const token = window.grecaptcha ? window.grecaptcha.getResponse() : null
  if (!token) {
    errors.value = 'Silakan verifikasi captcha dulu!'
    return
  }

  router.post('/login', {
    ...form.value,
    'g-recaptcha-response': token
  }, {
    onError: (err) => {
      errors.value = err.message || 'Login gagal'
      // Reset captcha supaya bisa submit ulang
      if (window.grecaptcha) window.grecaptcha.reset()
    }
  })
}
</script>
