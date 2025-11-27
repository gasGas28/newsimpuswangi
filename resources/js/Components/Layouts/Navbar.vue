<template>
  <nav class="navbar navbar-expand-lg navbar-dark shadow-sm sticky-top">
    <div class="container-fluid">
      <div class="d-flex flex-grow-1">
        <!-- Brand di kiri -->
        <Link href="/" class="navbar-brand fw-bold d-flex align-items-center">
          <!-- Logo -->
          <img
            src="../../../../public/images/Pukesmas.webp"
            alt="Logo SIMPUSWANGI"
            class="me-2"
            style="height: 38px; width: auto; border-radius: 8px;"
          />
          <span class="text-gradient">SIMPUSWANGI</span>
        </Link>

        <!-- Toggle button di kanan -->
        <button
          class="navbar-toggler ms-auto"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>

      <!-- Nav items di tengah -->
<div class="collapse navbar-collapse w-100" id="navbarNav">
  <ul class="navbar-nav mx-lg-auto"> <!-- mx-lg-auto = auto margin kiri-kanan di layar besar -->

          <!-- Dashboard -->
          <li class="nav-item" v-if="show('dashboard')">
            <Link :href="route('home.home')" class="nav-link d-flex align-items-center">
              <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </Link>
          </li>

          <!-- Loket -->
          <li class="nav-item" v-if="show('loket')">
            <Link :href="route('loket.index')" class="nav-link d-flex align-items-center">
              <i class="bi bi-ticket-perforated me-2"></i> Loket
            </Link>
          </li>
          <li class="nav-item">
            <Link class="nav-link" href="/farmasi">
              <i class="bi bi-capsule me-1"></i> Farmasi
            </Link>
          </li>

          <!-- Ruang Layanan -->
          <li class="nav-item" v-if="show('ruangLayanan')">
            <Link :href="route('ruang-layanan.poli')" class="nav-link d-flex align-items-center">
              <i class="bi bi-hospital me-2"></i> Ruang Layanan
            </Link>
          </li>
          <li class="nav-item" v-if="show('malSehat')">
            <Link :href="route('mal-sehat.index')" class="nav-link d-flex align-items-center">
              <i class="bi bi-people me-2"></i> Mal Sehat
            </Link>
          </li>

          <li class="nav-item" v-if="show('filter')">
            <Link :href="route('filter')" class="nav-link d-flex align-items-center">
              <i class="bi bi-filter-square-fill me-2"></i> Filter
            </Link>
          </li>

          <!-- Dropdown Laporan -->
          <li class="nav-item dropdown" v-if="show('laporan')">
            <a
              class="nav-link dropdown-toggle d-flex align-items-center"
              href="#"
              id="laporanDropdown"
              role="button"
              data-bs-toggle="dropdown"
              aria-expanded="false"
            >
              <i class="bi bi-file-earmark-bar-graph me-2"></i> Laporan
            </a>
            <ul class="dropdown-menu" aria-labelledby="laporanDropdown">
              <li><Link :href="route('laporan.loket')" class="dropdown-item">Loket</Link></li>
              <li><Link :href="route('laporan.rujukan')" class="dropdown-item">Rujukan</Link></li>
              <li><Link :href="route('laporan.umum')" class="dropdown-item">Umum</Link></li>
              <li><Link :href="route('laporan.gigi')" class="dropdown-item">Gigi</Link></li>
              <li><Link :href="route('laporan.kia')" class="dropdown-item">KIA</Link></li>
              <li><Link :href="route('laporan.lab')" class="dropdown-item">Lab</Link></li>
              <li><Link :href="route('laporan.kb')" class="dropdown-item">KB</Link></li>
              <li><Link :href="route('laporan.ugd')" class="dropdown-item">UGD</Link></li>
              <li><Link :href="route('laporan.rawat-inap')" class="dropdown-item">Rawat Inap</Link></li>
              <li><Link :href="route('laporan.sanitasi')" class="dropdown-item">Sanitasi</Link></li>
              <li><Link :href="route('laporan.kunjungan-sehat')" class="dropdown-item">Kunjungan Sehat</Link></li>
            </ul>
          </li>
        </ul>
      </div>

      <!-- Kanan: indikator ping + user dropdown -->
      <div class="d-flex align-items-center ms-auto gap-2">

        <!-- === Indikator Ping/Sinyal (desktop & mobile) === -->
        <div
          class="d-flex align-items-center px-2 py-1 rounded-3 ping-badge"
          :class="badgeClass"
          :title="tooltipTitle"
          data-bs-toggle="tooltip"
          data-bs-placement="bottom"
        >
          <i :class="iconClass" class="me-1"></i>
          <small class="fw-semibold">{{ pingLabel }}</small>
        </div>

        <!-- User dropdown (desktop) -->
        <div class="dropdown d-none d-lg-block">
          <button
            class="btn btn-outline-light rounded-pill dropdown-toggle d-flex align-items-center gap-2"
            type="button"
            id="userDropdown"
            data-bs-toggle="dropdown"
          >
            <i class="bi bi-person-circle"></i>
            <span class="d-none d-sm-inline">{{ roleLabel }}</span>
          </button>
<ul class="dropdown-menu dropdown-menu-end mt-2 border-0 shadow">
  <!-- Profile -->
  <li>
    <Link class="dropdown-item d-flex align-items-center"     :href="route('profile.index')"
>
      <i class="bi bi-person me-2"></i> Profile
    </Link>
  </li>

  <!-- Divider -->
  <li><hr class="dropdown-divider mx-3 my-1" /></li>

  <!-- Info Puskesmas -->
  <li class="px-3 py-2 text-center">
    <div class="d-flex flex-column align-items-center justify-content-center">
      <div
        class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-2 shadow-sm"
        style="width: 42px; height: 42px;"
      >
        <i class="bi bi-hospital text-primary fs-5"></i>
      </div>
      <div class="fw-semibold text-light-emphasis small">
        {{ puskesmasNama }}
      </div>
      <span class="text-secondary small">Puskesmas</span>
    </div>
  </li>

  <!-- Divider -->
  <li><hr class="dropdown-divider mx-3 my-1" /></li>

  <!-- Logout -->
  <li>
    <button
      @click="doLogout"
      class="dropdown-item d-flex align-items-center text-danger w-100 text-start"
    >
      <i class="bi bi-box-arrow-right me-2"></i> Logout
    </button>
  </li>
</ul>

        </div>

        <!-- User dropdown (mobile) -->
        <div class="dropdown d-lg-none">
          <button
            class="btn btn-outline-light rounded-pill dropdown-toggle d-flex align-items-center"
            type="button"
            id="userDropdownMobile"
            data-bs-toggle="dropdown"
          >
            <i class="bi bi-person-circle me-2"></i>
            <span class="d-none d-sm-inline">{{ roleLabel }}</span>
          </button>
          <ul class="dropdown-menu dropdown-menu-end mt-2 border-0 shadow">
            <li>
              <Link class="dropdown-item d-flex align-items-center" href="/profile">
                <i class="bi bi-person me-2"></i> Profile
              </Link>
            </li>
            <li><hr class="dropdown-divider mx-3 my-1" /></li>
            <li>
              <button
                @click="doLogout"
                class="dropdown-item d-flex align-items-center text-danger w-100 text-start"
              >
                <i class="bi bi-box-arrow-right me-2"></i> Logout
              </button>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { Link, usePage, router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import { ref, computed, onMounted, onBeforeUnmount, nextTick } from 'vue'

const page = usePage()

// roles: array dari server (Inertia share). Normalisasi ke lowercase.
const roles = computed(() =>
  (page.props.auth?.user?.roles || []).map(r => String(r).toLowerCase())
)

const roleLabel = computed(() => page.props.auth?.user?.roles?.[0] || 'User')
const puskesmasNama = computed(() => page.props.auth?.user?.puskesmas?.nama || '-')

// Akses menu
const ALLOW = {
  dashboard: ['owner','kapus'],
  loket: ['owner','loket','admin'],
  pasien: ['loket'],
  ruangLayanan: ['owner','pelayanan','admin','laborat'],
  malSehat: ['owner','admin'],
  laborat: ['pelayanan','laborat'],
  laporan: ['owner','loket','pelayanan','admin'],
  farmasi: ['owner','admin'],
  filter: ['owner','admin'],
}
const show = (menuKey) => {
  const allowed = ALLOW[menuKey]
  if (!allowed) return false
  return roles.value.some(r => allowed.includes(r))
}

const doLogout = () => {
  router.post(route('logout'), {}, { onSuccess: () => router.visit(route('login'), { replace: true }) })
}

/* ================================
   PING / INDICATOR LOGIC
   - Poll setiap 15 detik
   - Threshold:
     good < 100ms (hijau)
     ok   < 250ms (kuning)
     bad  >= 250ms atau timeout (merah)
   - Fetch ke /favicon.ico tanpa cache (nyaris selalu ada)
================================ */
const latencyMs = ref(null)          // number | null
const quality  = ref('checking')     // 'checking' | 'good' | 'ok' | 'bad' | 'down'
const lastAt   = ref(null)           // Date | null
const intervalMs = 3000
let ticker = null

const PING_URL = '/favicon.ico'
const TIMEOUT_MS = 5000

async function measurePing () {
  const start = performance.now()
  const ctrl = new AbortController()
  const timeout = setTimeout(() => ctrl.abort('timeout'), TIMEOUT_MS)

  try {
    // Gunakan GET + no-store agar tidak tersangkut cache/proxy
    const res = await fetch(PING_URL, { cache: 'no-store', signal: ctrl.signal, method: 'GET' })
    if (!res.ok) throw new Error(`HTTP ${res.status}`)
    const end = performance.now()
    const ms = Math.max(1, Math.round(end - start))
    latencyMs.value = ms
    lastAt.value = new Date()

    if (ms < 100)       quality.value = 'good'
    else if (ms < 250)  quality.value = 'ok'
    else                quality.value = 'bad'
  } catch (e) {
    latencyMs.value = null
    quality.value = 'down'
    lastAt.value = new Date()
  } finally {
    clearTimeout(timeout)
  }
}

const pingLabel = computed(() => {
  switch (quality.value) {
    case 'good':
      return '🟢 BAGUS'
    case 'ok':
      return '🟡 SEDANG'
    case 'bad':
      return '🔴 JELEK'
    case 'down':
      return '⚫ OFFLINE'
    default:
      return '⏳ CEK...'
  }
})


const iconClass = computed(() => {
  switch (quality.value) {
    case 'good': return 'bi bi-reception-4'
    case 'ok':   return 'bi bi-reception-2'
    case 'bad':  return 'bi bi-reception-0'
    case 'down': return 'bi bi-wifi-off'
    default:     return 'bi bi-reception-1'
  }
})

const badgeClass = computed(() => {
  switch (quality.value) {
    case 'good': return 'bg-success-subtle text-success-emphasis border border-success-subtle'
    case 'ok':   return 'bg-warning-subtle text-warning-emphasis border border-warning-subtle'
    case 'bad':  return 'bg-danger-subtle text-danger-emphasis border border-danger-subtle'
    case 'down': return 'bg-danger-subtle text-danger-emphasis border border-danger-subtle'
    default:     return 'bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle'
  }
})

const tooltipTitle = computed(() => {
  if (!lastAt.value) return 'Mengukur koneksi…'
  const ts = lastAt.value.toLocaleTimeString()
  if (quality.value === 'down') return `Terakhir cek ${ts} • koneksi bermasalah`
  if (quality.value === 'good') return `Terakhir cek ${ts} • koneksi bagus`
  if (quality.value === 'ok')   return `Terakhir cek ${ts} • koneksi sedang`
  if (quality.value === 'bad')  return `Terakhir cek ${ts} • koneksi jelek`
  return `Terakhir cek ${ts}`
})

onMounted(async () => {
  await nextTick()
  // Bootstrap tooltip (opsional, kalau Bootstrap JS sudah diimport global)
  if (window.bootstrap) {
    const els = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    els.forEach(el => new window.bootstrap.Tooltip(el))
  }
  // Ping awal + interval
  measurePing()
  ticker = setInterval(measurePing, intervalMs)
})

onBeforeUnmount(() => {
  if (ticker) clearInterval(ticker)
})
</script>

<style scoped>
.navbar {
  padding: 0.5rem 1rem;
  background-color: rgba(0, 0, 0);
}
.nav-link {
  padding: 0.5rem 1rem;
  border-radius: 0.5rem;
  transition: all 0.3s ease;
  font-weight: 500;
}
.nav-link:hover,
.nav-link.active {
  background-color: rgba(0, 0, 0, 0.1);
  transform: translateY(-1px);
}
.text-gradient {
  background: linear-gradient(90deg, #0d6efd, #00b4ff);
  -webkit-background-clip: text;
  background-clip: text;
  -webkit-text-fill-color: transparent;
}
.dropdown-menu {
  min-width: 200px;
  background-color: #2a2a2a;
}
.dropdown-item {
  color: #e0e0e0;
  padding: 0.5rem 1rem;
  border-radius: 0.25rem;
  margin: 0.15rem 0.5rem;
}
.dropdown-item:hover {
  background-color: #3a3a3a;
  color: white;
}
.btn-outline-light {
  border-width: 1.5px;
  padding: 0.375rem 1rem;
}
.navbar-brand {
  font-size: 1.25rem;
  letter-spacing: 0.5px;
}
/* Badge ping kecil rapi */
.ping-badge {
  line-height: 1;
  font-size: 12px;
}
@media (min-width: 992px) {
  .navbar-collapse {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
  }
}
</style>
