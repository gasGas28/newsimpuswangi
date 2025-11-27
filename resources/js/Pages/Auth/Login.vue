<script setup>
import { ref, onMounted, nextTick } from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'

axios.defaults.baseURL = 'http://127.0.0.1:8000';
axios.defaults.withCredentials = true;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.xsrfCookieName = 'XSRF-TOKEN';
axios.defaults.xsrfHeaderName = 'X-XSRF-TOKEN';
delete axios.defaults.headers.common['X-CSRF-TOKEN']; // pastikan ini tidak ada

// ====== State popup ganti password ======
const showForceModal = ref(false)
const newPass = ref('')
const newPassConfirm = ref('')
const savingNew = ref(false)
const newPassErr = ref('')
const pendingRedirect = ref('/') // redirect tujuan setelah ganti password

// ====== State login ======
const username = ref('')
const password = ref('')
const loading  = ref(false)
const siteKey  = import.meta.env.VITE_RECAPTCHA_SITE_KEY
const errors   = ref({ username: '', password: '', captcha: '' })
let widgetId   = null

// (aman) set CSRF header kalau meta tag ada
const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
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

const swalLoading = (title = 'Memverifikasi…') => {
  Swal.fire({
    title,
    allowEscapeKey: false,
    allowOutsideClick: false,
    showConfirmButton: false,
    didOpen: () => Swal.showLoading(),
    background: 'rgba(255,255,255,0.95)',
  })
}
const swalError = (msg = 'Terjadi kesalahan') => {
  Swal.fire({
    icon: 'error',
    title: 'Login gagal',
    text: msg,
    // ⭐ animasi manis
    showClass: { popup: 'animate__animated animate__fadeInDown' },
    hideClass: { popup: 'animate__animated animate__fadeOutUp' },
  })
}
const swalSuccess = (msg = 'Berhasil!') => {
  Swal.fire({
    icon: 'success',
    title: msg,
    timer: 1200,
    showConfirmButton: false,
    // ⭐ animasi manis
    showClass: { popup: 'animate__animated animate__fadeInDown' },
    hideClass: { popup: 'animate__animated animate__fadeOutUp' },
  })
}
// ⭐ Toast ringan di pojok (buat notifikasi singkat)
const swalToast = (title = 'Info', icon = 'info') => {
  Swal.fire({
    toast: true,
    position: 'top-end',
    timer: 2000,
    showConfirmButton: false,
    icon,
    title,
  })
}

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
    // ⭐ tampilkan spinner elegan
    swalLoading('Memverifikasi kredensial…')

    // ⚡ tambahkan ini sebelum axios.post — ambil CSRF cookie dari Laravel Sanctum
    await axios.get('/sanctum/csrf-cookie')

    // ambil CSRF cookie dulu
    await axios.get('/sanctum/csrf-cookie');

    // debug, kirim POST ke endpoint /_csrf-debug
    const debugRes = await axios.post('/_csrf-debug', {});
    console.log('CSRF-DEBUG', debugRes.data);
    await axios.get('/sanctum/csrf-cookie');   // set cookie
    const dbg = await axios.post('/_csrf-debug', {}); // harus 200, bukan 419
    console.log(dbg.data);

    // baru kirim login (pakai nama res yg lama)
    const res = await axios.post('/login', {
      username: username.value,
      password: password.value,
      'g-recaptcha-response': recaptchaToken,
    });
    // baru kirim login

    const needChange = !!res?.data?.require_password_change
    const redirectUrl = res?.data?.redirect ?? '/dashboard'
    Swal.close() // ⭐ tutup spinner

    if (needChange) {
      pendingRedirect.value = redirectUrl
      showForceModal.value = true
      if (window.grecaptcha && widgetId !== null) window.grecaptcha.reset(widgetId)
    } else {
      window.location.href = redirectUrl
    }
  } catch (e) {
    if (window.grecaptcha && widgetId !== null) window.grecaptcha.reset(widgetId)
    Swal.close() // ⭐ pastikan spinner tertutup

    if (e?.response?.status === 422) {
      const errs = e.response.data.errors || {}
      errors.value.username = errs.username?.[0] || ''
      errors.value.password = errs.password?.[0] || ''
      errors.value.captcha  = errs.captcha?.[0] || errs['g-recaptcha-response']?.[0] || ''

      // ⭐ tampilkan ringkas sebagai toast (biar konsisten dengan punyamu)
      swalToast('Cek kembali isian kamu', 'warning')

      // ⭐ TAMBAHAN: popup detail dengan pesan pertama yang ada
      const firstError =
        errors.value.username || errors.value.password || errors.value.captcha
      if (firstError) {
        Swal.fire({
          icon: 'error',
          title: 'Login gagal',
          text: firstError,
          confirmButtonColor: '#2d6a4f',
          background: 'rgba(255,255,255,0.95)',
          showClass: { popup: 'animate__animated animate__fadeInDown' },
          hideClass: { popup: 'animate__animated animate__fadeOutUp' },
        })
      }
      return
    }
    // tetap pakai helper error milikmu
    swalError(e?.response?.data?.message || 'Username/password salah atau server bermasalah.')
  } finally {
    loading.value = false
  }
  return
}

async function saveNewPassword() {
  newPassErr.value = ''

  if (!newPass.value || newPass.value.length < 8) {
    newPassErr.value = 'Minimal 8 karakter.'
    return
  }
  if (newPass.value !== newPassConfirm.value) {
    newPassErr.value = 'Konfirmasi password tidak cocok.'
    return
  }

  savingNew.value = true
  try {
    // Endpoint backend yg kita buat: /auth/password/force-update
    await axios.post('/auth/password/force-update', {
      new_password: newPass.value,
      new_password_confirmation: newPassConfirm.value,
    })

    // Tutup modal dan redirect ke halaman tujuan
    showForceModal.value = false
    window.location.href = pendingRedirect.value || '/dashboard'
  } catch (e) {
    newPassErr.value = e?.response?.data?.message || 'Gagal menyimpan password baru.'
    // ⭐ tampilkan juga sebagai popup agar konsisten UX
    Swal.fire({
      icon: 'error',
      title: 'Gagal menyimpan password',
      text: newPassErr.value,
      confirmButtonColor: '#2d6a4f',
      background: 'rgba(255,255,255,0.95)',
      showClass: { popup: 'animate__animated animate__fadeInDown' },
      hideClass: { popup: 'animate__animated animate__fadeOutUp' },
    })
  } finally {
    savingNew.value = false
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
        <p class="small mt-3 text-muted mb-4 mb-md-0">&copy; 2025 Puskesmas App<br />Powered by FAIZ</p>
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
            <button :disabled="loading" type="submit" class="btn login-btn rounded-pill py-2 fw-semibold d-inline-flex align-items-center justify-content-center">
              <span v-if="!loading">Login</span>
              <span v-else class="d-inline-flex align-items-center">
                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                Memproses…
              </span>
            </button>
          </div>
        </form>
      </div>
    </div>

  </div>

  <!-- Modal Paksa Ganti Password (muncul setelah login sukses & expired) -->
  <div v-if="showForceModal"
       class="modal fade show"
       style="display:block; background: rgba(0,0,0,.35);"
       data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content rounded-4">
        <div class="modal-header">
          <h5 class="modal-title">Password Kedaluwarsa</h5>
        </div>
        <div class="modal-body">
          <p class="text-muted mb-3">Password Anda sudah lebih dari 15 hari. Silakan perbarui untuk melanjutkan.</p>

          <div class="mb-2">
            <label class="form-label">Password Baru</label>
            <input type="password" v-model="newPass" class="form-control" />
          </div>
          <div class="mb-2">
            <label class="form-label">Konfirmasi Password Baru</label>
            <input type="password" v-model="newPassConfirm" class="form-control" />
          </div>

          <div v-if="newPassErr" class="text-danger small mt-2">{{ newPassErr }}</div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary w-100" :disabled="savingNew" @click="saveNewPassword">
            {{ savingNew ? 'Menyimpan...' : 'Simpan Password' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
/* ⭐ animasi untuk SweetAlert */
@import 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css';

/* ====== WRAPPER ====== */
.login-page {
  background:
    linear-gradient(-45deg, rgba(142,202,230,.35), rgba(214,245,236,.35), rgba(167,214,193,.35), rgba(224,247,239,.35)),
    url('../../../../public/images/Background.png') center center / cover no-repeat;
  animation: gradientMove 15s ease infinite;
  font-family: 'Poppins', sans-serif;
  position: relative;
  overflow: hidden;
  z-index: 0;
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
  min-height: 520px;
}
@media (max-width: 767.98px) { .login-card { min-height: unset; } }

/* ====== PANELS ====== */
.left-panel {
  background-color: #ffffff;
  padding: 2rem;
  z-index: 1;
  clip-path: polygon(0 0, 85% 0, 70% 100%, 0% 100%);
}
@media (max-width: 767.98px) {
  .left-panel { clip-path: none; padding: 1.5rem 1rem 2rem; }
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
.bubbles { position: absolute; inset: 0; margin: 0; padding: 0; overflow: hidden; z-index: 0; list-style: none; }
.bubbles li {
  position: absolute;
  bottom: -120px;
  border-radius: 50%;
  animation: bubbleFloat 20s linear infinite;
  box-shadow: 0 0 20px rgba(129,199,132,0.2);
}
@keyframes bubbleFloat {
  0% { transform: translateY(0) scale(0.8); opacity: 0.3; }
  50% { transform: translateY(-500px) scale(1.1); opacity: 0.8; }
  100% { transform: translateY(-1000px) scale(1.3); opacity: 0; }
}

/* ====== RAIN ====== */
.rain { position: absolute; inset: 0; pointer-events: none; z-index: 0; }
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
.particle { position: absolute; background: white; border-radius: 50%; z-index: 1; }

/* ====== SPACING TUNING SMALL SCREENS ====== */
@media (max-width: 575.98px) { .right-panel { padding: 1.25rem !important; } }
</style>
