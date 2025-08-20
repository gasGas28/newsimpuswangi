<script setup>
import { ref, onMounted, nextTick } from 'vue'
import axios from 'axios'

const username = ref('')
const password = ref('')
const loading  = ref(false)
const siteKey  = import.meta.env.VITE_RECAPTCHA_SITE_KEY
const errors   = ref({ username: '', password: '', captcha: '' })
let widgetId   = null

// (aman) set CSRF header kalau meta tag ada
const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
if (csrf) axios.defaults.headers.common['X-CSRF-TOKEN'] = csrf
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

// Loader reCAPTCHA idempotent
const loadRecaptcha = () => {
  if (window.grecaptcha) return Promise.resolve(window.grecaptcha)

  const existed = document.querySelector('#recaptcha-script')
  if (existed) {
    return new Promise((resolve, reject) => {
      existed.addEventListener('load', () => resolve(window.grecaptcha))
      existed.addEventListener('error', reject)
    })
  }

  return new Promise((resolve, reject) => {
    const s = document.createElement('script')
    s.id = 'recaptcha-script'
    s.src = 'https://www.google.com/recaptcha/api.js?render=explicit'
    s.async = true
    s.defer = true
    s.onload = () => resolve(window.grecaptcha)
    s.onerror = () => reject(new Error('Failed to load reCAPTCHA'))
    document.head.appendChild(s)
  })
}

onMounted(async () => {
  const grecaptcha = await loadRecaptcha()
  await nextTick()
  widgetId = grecaptcha.render('recaptcha-box', {
    sitekey: siteKey,
    theme: 'light', // bisa 'dark'
  })
})

const submit = async () => {
  errors.value = { username: '', password: '', captcha: '' }

  if (!username.value) errors.value.username = 'Username wajib diisi.'
  if (!password.value) errors.value.password = 'Password wajib diisi.'

  // ✅ ambil token langsung dari grecaptcha
  const recaptchaToken = window.grecaptcha?.getResponse(widgetId) || ''
  if (!recaptchaToken) errors.value.captcha = 'Tolong centang reCAPTCHA dulu.'

  if (errors.value.username || errors.value.password || errors.value.captcha) return

  loading.value = true
  try {
    const res = await axios.post('/login', {
      username: username.value,
      password: password.value,
      'g-recaptcha-response': recaptchaToken,
    })
    const redirectUrl = res?.data?.redirect ?? '/dashboard'
    window.location.href = redirectUrl
  } catch (e) {
    if (window.grecaptcha && widgetId !== null) window.grecaptcha.reset(widgetId)

    if (e?.response?.status === 422) {
      const errs = e.response.data.errors || {}
      errors.value.username = errs.username?.[0] || ''
      errors.value.password = errs.password?.[0] || ''
      errors.value.captcha  = errs.captcha?.[0] || errs['g-recaptcha-response']?.[0] || ''
      return
    }
    alert(e?.response?.data?.message || 'Login gagal')
  } finally {
    loading.value = false
  }
}

</script>


<template>
  <div class="login-page d-flex align-items-center justify-content-center px-3 py-5 position-relative">

    <!-- Efek HUJAN -->
    <div class="rain d-none d-sm-block">
      <div
        class="raindrop"
        v-for="i in 40"
        :key="`rain-${i}`"
        :style="{
          left: `${Math.random() * 100}%`,
          animationDelay: `${Math.random() * 5}s`,
          animationDuration: `${Math.random() * 1.5 + 0.5}s`
        }"
      />
    </div>

    <!-- Efek PARTIKEL -->
    <div
      class="particle d-none d-md-block"
      v-for="i in 40"
      :key="`p-${i}`"
      :style="{
        top: `${Math.random()*100}%`,
        left: `${Math.random()*100}%`,
        width: `${Math.random()*3+1}px`,
        height: `${Math.random()*3+1}px`,
        opacity: `${Math.random()*0.3 + 0.1}`
      }"
    />

    <!-- Efek BUBBLE -->
    <ul class="bubbles d-none d-lg-block">
      <li
        v-for="i in 25"
        :key="`bubble-${i}`"
        :style="{
          width: `${Math.random() * 40 + 20}px`,
          height: `${Math.random() * 40 + 20}px`,
          left: `${Math.random() * 100}%`,
          animationDuration: `${Math.random() * 10 + 10}s`,
          backgroundColor: 'rgba(129, 199, 132, 0.2)'
        }"
      />
    </ul>

    <!-- LOGIN CARD -->
    <div class="login-card row g-0 shadow-lg overflow-hidden position-relative z-1 w-100"
         style="max-width: 900px;">
      
      <!-- Panel Kiri -->
      <div class="left-panel col-12 col-md-6 d-flex flex-column justify-content-center align-items-center text-center order-2 order-md-1">
        <img
          src="../../../../public/images/Pukesmas.png"
          alt="Puskesmas Illustration"
          class="img-fluid px-5 px-md-4"
          style="max-height: 260px"
        />
        <p class="small mt-3 text-muted mb-4 mb-md-0">&copy; 2025 Puskesmas App<br />Powered by NOC</p>
      </div>

      <!-- Panel Kanan -->
      <div class="right-panel col-12 col-md-6 text-white d-flex flex-column justify-content-center p-4 p-md-5 order-1 order-md-2">
        <h4 class="fw-semibold mb-4 text-center text-md-start">Login</h4>

        <form @submit.prevent="submit">
          <div class="mb-3">
            <label class="form-label small text-white-50">Username</label>
            <input v-model="username" type="text" class="form-control input-custom" placeholder="Enter your username" :class="{'is-invalid': errors.username}" />
            <div v-if="errors.username" class="invalid-feedback d-block">{{ errors.username }}</div>
          </div>

          <div class="mb-3">
            <label class="form-label small text-white-50">Password</label>
            <input v-model="password" type="password" class="form-control input-custom" placeholder="Enter your password" :class="{'is-invalid': errors.password}" />
            <div v-if="errors.password" class="invalid-feedback d-block">{{ errors.password }}</div>
          </div>

          <!-- reCAPTCHA widget -->
          <div id="recaptcha-box" class="mb-2"></div>
          <div v-if="errors.captcha" class="invalid-feedback d-block mb-3">{{ errors.captcha }}</div>

          <div class="d-grid mb-3">
            <button :disabled="loading" type="submit" class="btn login-btn rounded-pill py-2 fw-semibold">
              <span v-if="!loading">Login</span>
              <span v-else>Loading…</span>
            </button>
          </div>

          <div class="text-center small">
            <span class="text-white-50">Don't have an account?</span>
            <a href="#" class="text-info text-decoration-none">Register Now</a>
          </div>

          <div class="text-center mt-3 small">
            <a href="#" class="text-white-50 text-decoration-underline">Terms and Services</a><br />
            <span class="text-white-50">Need help? <a href="mailto:support@email.com" class="text-info">Contact us</a></span>
          </div>
        </form>
      </div>
    </div>

  </div>
</template>

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

/* ====== WRAPPER ====== */
.login-page {
  /* Gradient overlay + image cover agar selalu full */
  background:
    linear-gradient(-45deg, rgba(142,202,230,.35), rgba(214,245,236,.35), rgba(167,214,193,.35), rgba(224,247,239,.35)),
    url('../../../../public/images/Background.png') center center / cover no-repeat;
  animation: gradientMove 15s ease infinite;
  font-family: 'Poppins', sans-serif;
  position: relative;
  overflow: hidden;
  z-index: 0;

  /* full height di semua layar */
  min-height: 100vh;
}

/* animasi subtle untuk overlay gradient saja */
@keyframes gradientMove {
  0% { background-position: 0% 50%, center; }
  50% { background-position: 100% 50%, center; }
  100% { background-position: 0% 50%, center; }
}

/* ====== CARD ====== */
.login-card {
  display: flex;
  border-radius: 20px;
  overflow: hidden;
  backdrop-filter: blur(10px);
  background-color: rgba(255,255,255,0.1);
  box-shadow: 0 8px 32px 0 rgba(31,38,135,0.37);
  border: 1px solid rgba(255,255,255,0.18);
  /* tinggi fleksibel; di desktop kita kasih min-height mirip desain awal */
  min-height: 520px;
}

/* di mobile biar tidak kepotong, tinggi auto */
@media (max-width: 767.98px) {
  .login-card {
    min-height: unset;
  }
}

/* ====== PANELS ====== */
.left-panel {
  background-color: #ffffff;
  padding: 2rem;
  z-index: 1;
  /* clip hanya di desktop supaya tidak “nyenggol” konten saat mobile */
  clip-path: polygon(0 0, 85% 0, 70% 100%, 0% 100%);
}
@media (max-width: 767.98px) {
  .left-panel {
    clip-path: none;
    padding: 1.5rem 1rem 2rem;
  }
}

.right-panel {
  background: linear-gradient(160deg, #2d6a4f, #1b4332);
  color: white;
  padding: 2rem;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

/* ====== FORM INPUTS ====== */
.input-custom {
  background-color: rgba(255,255,255,0.1);
  border: 1px solid #dee2e6;
  color: white;
  border-radius: 50px;
  padding: 0.6rem 1rem;
  transition: all 0.3s ease;
}
.input-custom::placeholder { color: rgba(255,255,255,0.5); }
.input-custom:focus {
  outline: none;
  background-color: rgba(255,255,255,0.2);
  border-color: #80ed99;
  box-shadow: 0 0 0 0.2rem rgba(129,199,132,0.25);
}

.login-btn {
  background: linear-gradient(to right, #38b000, #70e000);
  border: none;
  transition: background 0.3s ease;
}
.login-btn:hover {
  background: linear-gradient(to right, #70e000, #38b000);
}

/* ====== BUBBLES ====== */
.bubbles {
  position: absolute;
  inset: 0;
  margin: 0;
  padding: 0;
  overflow: hidden;
  z-index: 0;
  list-style: none;
}
.bubbles li {
  position: absolute;
  bottom: -120px;
  border-radius: 50%;
  animation: bubbleFloat 20s linear infinite;
  box-shadow: 0 0 20px rgba(129,199,132,0.2);
}
@keyframes bubbleFloat {
  0%   { transform: translateY(0) scale(0.8); opacity: 0.3; }
  50%  { transform: translateY(-500px) scale(1.1); opacity: 0.8; }
  100% { transform: translateY(-1000px) scale(1.3); opacity: 0; }
}

/* ====== RAIN ====== */
.rain {
  position: absolute;
  inset: 0;
  pointer-events: none;
  z-index: 0;
}
.raindrop {
  position: absolute;
  top: -10%;
  width: 2px;
  height: 25px;
  background: rgba(0,0,0,0.15);
  animation: rainFall linear infinite;
  border-radius: 1px;
}
@keyframes rainFall { to { transform: translateY(120vh); } }

/* ====== PARTICLES ====== */
.particle {
  position: absolute;
  background: white;
  border-radius: 50%;
  z-index: 1;
}

/* ====== SPACING TUNING SMALL SCREENS ====== */
@media (max-width: 575.98px) {
  .right-panel { padding: 1.25rem !important; }
}
</style>
