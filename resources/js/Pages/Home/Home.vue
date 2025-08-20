<template>
  <AppLayout title="Home">
    <div class="container-fluid py-4" style="font-family: 'Segoe UI', sans-serif;">

      <!-- FILTER -->
      <div class="card mb-4 shadow-sm">
        <div
          class="card-header fw-bold text-white d-flex align-items-center justify-content-between"
          style="background: linear-gradient(135deg, #0d6efd, #20c997); transition: 0.3s;"
        >
          <span>Filter Home</span>
          <small class="opacity-75">Default: Per Hari</small>
        </div>
        <div class="card-body">
          <form @submit.prevent>
            <!-- Mode -->
            <div class="row mb-3">
              <label class="col-md-2 col-form-label fw-semibold">Mode</label>
              <div class="col-md-6 d-flex gap-4 align-items-center">
                <div class="form-check">
                  <input class="form-check-input" type="radio" id="modeHarian" value="harian" v-model="filters.mode">
                  <label class="form-check-label" for="modeHarian">Per Hari</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" id="modeRentang" value="rentang" v-model="filters.mode">
                  <label class="form-check-label" for="modeRentang">Rentang</label>
                </div>
              </div>
            </div>

            <!-- Per Hari -->
            <div class="row mb-3" v-if="filters.mode==='harian'">
              <label class="col-md-2 col-form-label fw-semibold">Tanggal</label>
              <div class="col-md-6">
                <input type="date" class="form-control" v-model="filters.date" />
              </div>
            </div>

            <!-- Rentang -->
            <div class="row mb-3" v-else>
              <label class="col-md-2 col-form-label fw-semibold">Rentang</label>
              <div class="col-md-3 mb-2 mb-md-0">
                <input type="date" class="form-control" v-model="filters.start" />
              </div>
              <div class="col-md-3">
                <input type="date" class="form-control" v-model="filters.end" />
              </div>
            </div>

            <div class="row">
              <div class="col-md-8 offset-md-2 d-flex flex-wrap gap-3">
                <button type="button" class="btn btn-gradient text-white border-0 px-4 py-2 fw-semibold rounded" @click="applyFilter">
                  <i class="bi bi-funnel me-1"></i> Terapkan
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- ROW 1: BAR FULL WIDTH -->
      <div class="card h-100 shadow-sm mb-4">
        <div class="card-header fw-bold d-flex align-items-center justify-content-between flex-wrap gap-2">
          <span>Kunjungan Per Hari (L/P)</span>
          <div class="d-flex align-items-center gap-2">
            <span class="badge rounded-pill text-bg-primary-subtle border text-primary">Laki-laki: {{ totalMaleRange }}</span>
            <span class="badge rounded-pill text-bg-danger-subtle border text-danger">Perempuan: {{ totalFemaleRange }}</span>
          </div>
        </div>
        <div class="card-body p-0">
          <div class="chart-wrap">
            <canvas ref="barRef"></canvas>
          </div>
        </div>
      </div>

      <!-- ROW 2: DONUTS (2 kolom sejajar) -->
      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <div class="card shadow-sm h-100">
            <div class="card-header fw-bold d-flex align-items-center justify-content-between">
              <span>Jenis Kelamin</span>
              <div class="d-flex gap-2">
                <span class="badge rounded-pill text-bg-primary-subtle border text-primary">Laki-laki: {{ gender.male }}</span>
                <span class="badge rounded-pill text-bg-danger-subtle border text-danger">Perempuan: {{ gender.female }}</span>
              </div>
            </div>
            <div class="card-body">
              <div class="chart-donut">
                <canvas ref="donutGenderRef" height="220"></canvas>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card shadow-sm h-100">
            <div class="card-header fw-bold d-flex align-items-center justify-content-between">
              <span>Jenis Pembiayaan</span>
              <div class="d-flex gap-2">
                <span class="badge rounded-pill text-bg-success-subtle border text-success">BPJS: {{ payment.bpjs }}</span>
                <span class="badge rounded-pill text-bg-secondary-subtle border text-secondary">Non-BPJS: {{ payment.non_bpjs }}</span>
              </div>
            </div>
            <div class="card-body">
              <div class="chart-donut">
                <canvas ref="donutPaymentRef" height="220"></canvas>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card shadow-sm h-100">
            <div class="card-header fw-bold d-flex align-items-center justify-content-between">
              <span>Tipe Kunjungan</span>
              <div class="d-flex gap-2">
                <span class="badge rounded-pill text-bg-info-subtle border text-info">Baru: {{ visit.baru }}</span>
                <span class="badge rounded-pill text-bg-dark-subtle border">Lama: {{ visit.lama }}</span>
              </div>
            </div>
            <div class="card-body">
              <div class="chart-donut">
                <canvas ref="donutVisitRef" height="220"></canvas>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card shadow-sm h-100">
            <div class="card-header fw-bold d-flex align-items-center justify-content-between">
              <span>Status Rujukan</span>
              <div class="d-flex gap-2">
                <span class="badge rounded-pill text-bg-primary-subtle border text-primary">Internal: {{ referral.internal }}</span>
                <span class="badge rounded-pill text-bg-warning-subtle border text-warning">Rujukan: {{ referral.rujukan }}</span>
              </div>
            </div>
            <div class="card-body">
              <div class="chart-donut">
                <canvas ref="donutReferralRef" height="220"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ROW 3: TOP 10 PENYAKIT FULL -->
      <div class="card mt-4 shadow-sm">
        <div class="card-header fw-bold">10 Penyakit Terbesar</div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-sm align-middle">
              <thead class="table-light">
                <tr>
                  <th>#</th>
                  <th>Kode</th>
                  <th>Nama Penyakit</th>
                  <th class="text-end">Total</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(row, idx) in topDiseases" :key="idx">
                  <td>{{ idx + 1 }}</td>
                  <td><span class="badge bg-primary-subtle text-primary border">{{ row.kode }}</span></td>
                  <td>{{ row.nama }}</td>
                  <td class="text-end fw-semibold">{{ row.total }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import AppLayout from '@/Components/Layouts/AppLayouts.vue'

// ====== PROPS dari Inertia ======
const page = usePage()
const initial = page.props

// Filters (ambil dari server, biar konsisten dengan normalisasi di backend)
const filters = ref({
  mode: initial.filters?.mode ?? 'harian',
  date: initial.filters?.date ?? (initial.serverNow ?? new Date().toISOString().slice(0,10)),
  start: initial.filters?.start ?? initial.serverNow,
  end: initial.filters?.end ?? initial.serverNow,
  pusk_id: initial.filters?.pusk_id ?? null,
})

// Data dari server
const perDayAll    = ref(initial.perDayAll ?? [])
const gender       = ref(initial.gender ?? { male: 0, female: 0 })
const payment      = ref(initial.payment ?? { bpjs: 0, non_bpjs: 0 })
const visit        = ref(initial.visit ?? { baru: 0, lama: 0 })
const referral     = ref(initial.referral ?? { internal: 0, rujukan: 0 })
const topDiseases  = ref(initial.topDiseases ?? [])

// Terapkan filter → reload via Inertia (Ziggy route('home.home'))
function applyFilter() {
  const params = {
    mode: filters.value.mode,
    date: filters.value.date,
    start: filters.value.start,
    end: filters.value.end,
  }
  if (filters.value.pusk_id) params.pusk_id = filters.value.pusk_id

  router.get(route('home.home'), params, {
    preserveScroll: true,
    preserveState: true,
    replace: true,
    onSuccess: () => {
      // ambil data terbaru dari props
      const p = usePage().props

      // *** penting: sinkronkan filter dari server (yang sudah dinormalisasi) ***
      if (p.filters) {
        filters.value = {
          mode: p.filters.mode,
          date: p.filters.date,
          start: p.filters.start,
          end: p.filters.end,
          pusk_id: p.filters.pusk_id ?? null,
        }
      }

      perDayAll.value   = p.perDayAll ?? []
      gender.value      = p.gender ?? { male: 0, female: 0 }
      payment.value     = p.payment ?? { bpjs: 0, non_bpjs: 0 }
      visit.value       = p.visit ?? { baru: 0, lama: 0 }
      referral.value    = p.referral ?? { internal: 0, rujukan: 0 }
      topDiseases.value = p.topDiseases ?? []

      // refresh donut
      updateDonuts()
    }
  })
}

// ====== Data by filter (client-side subset untuk bar chart)
const perDay = computed(() => {
  if (filters.value.mode === 'harian') {
    return perDayAll.value.filter(d => d.date === filters.value.date)
  }
  const start = filters.value.start
  const end   = filters.value.end
  return perDayAll.value.filter(d => d.date >= start && d.date <= end)
})

// ====== Totals (untuk badge header bar) ======
const totalMaleRange   = computed(() => perDay.value.reduce((s, d) => s + (d.male || 0), 0))
const totalFemaleRange = computed(() => perDay.value.reduce((s, d) => s + (d.female || 0), 0))

// ====== Chart stuff ======
const barRef = ref(null)
const donutGenderRef = ref(null)
const donutPaymentRef = ref(null)
const donutVisitRef = ref(null)
const donutReferralRef = ref(null)
const chartMode = ref('stacked') // 'stacked' | 'grouped' | 'percent'
let ChartLib = null
let barChart = null

function setMode(mode) { chartMode.value = mode }

const centerTextPlugin = {
  id: 'centerText',
  afterDraw(chart, args, pluginOptions) {
    const type = chart.config.type
    if (type !== 'doughnut' && type !== 'pie') return
    const { ctx, chartArea } = chart
    if (!chartArea) return
    const ds = chart.data.datasets?.[0]
    if (!ds) return
    const total = (ds.data || []).reduce((a, b) => a + (Number(b) || 0), 0)
    const text = (pluginOptions && pluginOptions.formatter)
      ? pluginOptions.formatter(total)
      : `Total: ${total}`
    ctx.save()
    ctx.font = '600 14px Segoe UI, sans-serif'
    ctx.textAlign = 'center'
    ctx.textBaseline = 'middle'
    ctx.fillStyle = '#495057'
    const x = (chartArea.left + chartArea.right) / 2
    const y = (chartArea.top + chartArea.bottom) / 2
    ctx.fillText(text, x, y)
    ctx.restore()
  }
}

// simpan instance donut utk bisa di-update
let donutGenderChart = null
let donutPaymentChart = null
let donutVisitChart = null
let donutReferralChart = null

function updateDonuts() {
  if (donutGenderChart) {
    donutGenderChart.data.datasets[0].data = [gender.value.male, gender.value.female]
    donutGenderChart.update()
  }
  if (donutPaymentChart) {
    donutPaymentChart.data.datasets[0].data = [payment.value.bpjs, payment.value.non_bpjs]
    donutPaymentChart.update()
  }
  if (donutVisitChart) {
    donutVisitChart.data.datasets[0].data = [visit.value.baru, visit.value.lama]
    donutVisitChart.update()
  }
  if (donutReferralChart) {
    donutReferralChart.data.datasets[0].data = [referral.value.internal, referral.value.rujukan]
    donutReferralChart.update()
  }
}

function buildBarConfig() {
  const blue = 'rgba(77, 171, 247, 0.9)'
  const pink = 'rgba(247, 131, 172, 0.9)'
  const grid = '#e9ecef'
  const border = '#dee2e6'

  let labels = perDay.value.map(d => d.date)
  let datasets = []
  let stacked = false
  let yMax, yTicks

  if (chartMode.value === 'stacked') {
    stacked = true
    datasets = [
      { label: 'Laki-laki', data: perDay.value.map(d => d.male), backgroundColor: blue, borderWidth: 0, barPercentage: 0.85, categoryPercentage: 0.7, stack: 'v' },
      { label: 'Perempuan', data: perDay.value.map(d => d.female), backgroundColor: pink, borderWidth: 0, barPercentage: 0.85, categoryPercentage: 0.7, stack: 'v', borderRadius: { topLeft: 8, topRight: 8 } },
    ]
  } else if (chartMode.value === 'grouped') {
    stacked = false
    datasets = [
      { label: 'Laki-laki', data: perDay.value.map(d => d.male), backgroundColor: blue, borderWidth: 0, barPercentage: 0.9, categoryPercentage: 0.7 },
      { label: 'Perempuan', data: perDay.value.map(d => d.female), backgroundColor: pink, borderWidth: 0, barPercentage: 0.9, categoryPercentage: 0.7 },
    ]
  } else {
    stacked = true
    yMax = 100
    yTicks = { callback: v => v + '%' }
    const toPct = (key) => perDay.value.map(d => {
      const t = (d.male || 0) + (d.female || 0)
      return t ? Math.round(((d[key] || 0) / t) * 100) : 0
    })
    datasets = [
      { label: 'Laki-laki', data: toPct('male'), backgroundColor: blue, borderWidth: 0, barPercentage: 0.85, categoryPercentage: 0.7, stack: 'v' },
      { label: 'Perempuan', data: toPct('female'), backgroundColor: pink, borderWidth: 0, barPercentage: 0.85, categoryPercentage: 0.7, stack: 'v', borderRadius: { topLeft: 8, topRight: 8 } },
    ]
  }

  return {
    type: 'bar',
    data: { labels, datasets },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      layout: { padding: { left: 0, right: 0, top: 8, bottom: 8 } },
      interaction: { mode: 'index', intersect: false },
      scales: {
        x: { stacked, offset: true, grid: { color: grid, drawBorder: true, borderColor: border }, ticks: { autoSkip: true, maxRotation: 0, minRotation: 0 } },
        y: { stacked, beginAtZero: true, grace: '10%', suggestedMax: yMax, grid: { color: grid, drawBorder: true, borderColor: border }, ticks: yTicks || { precision: 0 } },
      },
      plugins: {
        legend: { position: 'top', labels: { boxWidth: 14, boxHeight: 14 } },
        tooltip: chartMode.value === 'percent'
          ? { callbacks: { label: (ctx) => `${ctx.dataset.label}: ${ctx.parsed.y}%`, footer: () => 'Total: 100%' } }
          : { callbacks: { footer: (items) => 'Total: ' + items.reduce((s, i) => s + i.parsed.y, 0) } },
      },
      animation: { duration: 300 },
    }
  }
}

onMounted(async () => {
  ChartLib = (await import('chart.js/auto')).default
  ChartLib.register(centerTextPlugin)

  // Bar awal
  barChart = new ChartLib(barRef.value, buildBarConfig())

  // Donut common options
  const commonOpts = (formatter) => ({
    responsive: true,
    maintainAspectRatio: false,
    cutout: '60%',
    plugins: { legend: { position: 'bottom' }, centerText: { formatter } }
  })

  // simpan instance donut biar bisa di-update
  donutGenderChart = new ChartLib(donutGenderRef.value, {
    type: 'doughnut',
    data: { labels: ['Laki-laki', 'Perempuan'], datasets: [{ data: [gender.value.male, gender.value.female] }] },
    options: commonOpts(total => `Total: ${total}`)
  })
  donutPaymentChart = new ChartLib(donutPaymentRef.value, {
    type: 'doughnut',
    data: { labels: ['BPJS', 'Non-BPJS'], datasets: [{ data: [payment.value.bpjs, payment.value.non_bpjs] }] },
    options: commonOpts(total => `Total: ${total}`)
  })
  donutVisitChart = new ChartLib(donutVisitRef.value, {
    type: 'doughnut',
    data: { labels: ['Baru', 'Lama'], datasets: [{ data: [visit.value.baru, visit.value.lama] }] },
    options: commonOpts(total => `Total: ${total}`)
  })
  donutReferralChart = new ChartLib(donutReferralRef.value, {
    type: 'doughnut',
    data: { labels: ['Internal', 'Rujukan'], datasets: [{ data: [referral.value.internal, referral.value.rujukan] }] },
    options: commonOpts(total => `Total: ${total}`)
  })
})

// Re-render bar kalau data/mode berubah
watch([perDay, chartMode, perDayAll], () => {
  if (!barChart || !ChartLib) return
  barChart.destroy()
  barChart = new ChartLib(barRef.value, buildBarConfig())
})
</script>

<style scoped>
/* ...style lain tetap... */
.chart-wrap {
  position: relative;
  width: 100%;
  /* Naikkan tinggi canvas (dulu 420px) */
  height: 520px; /* atau 600px kalau mau lebih tinggi */
}
.chart-wrap canvas,
.chart-donut canvas {
  display: block;
  width: 100% !important;
  height: 100% !important;
}
</style>