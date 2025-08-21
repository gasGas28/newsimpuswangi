<template>
  <AppLayout title="Home">
    <div class="container-fluid py-4" style="font-family: 'Segoe UI', sans-serif;">

      <!-- FILTER (rentang tanggal) -->
      <div class="card mb-4 shadow-sm">
        <div
          class="card-header fw-bold text-white d-flex align-items-center justify-content-between"
          style="background: linear-gradient(135deg, #0d6efd, #20c997);"
        >
          <span>Filter Home</span>
          <small class="opacity-75">Rentang Tanggal</small>
        </div>
        <div class="card-body">
          <form @submit.prevent>
            <div class="row mb-3 align-items-center">
              <label class="col-md-2 col-form-label fw-semibold">Tanggal</label>
              <div class="col-md-3">
                <input type="date" class="form-control" v-model="filters.start_date" />
              </div>
              <div class="col-md-1 text-center">s/d</div>
              <div class="col-md-3">
                <input type="date" class="form-control" v-model="filters.end_date" />
              </div>
            </div>

            <div class="row">
              <div class="col-md-8 offset-md-2 d-flex flex-wrap gap-3">
                <button
                  type="button"
                  class="btn btn-primary px-4 py-2 fw-semibold rounded"
                  :disabled="!isRangeValid"
                  @click="applyFilter"
                  title="Tampilkan data sesuai rentang"
                >
                  <i class="bi bi-eye me-1"></i> Tampilkan
                </button>

                <button
                  type="button"
                  class="btn btn-outline-secondary px-4 py-2 fw-semibold rounded"
                  @click="resetFilter"
                >
                  Reset
                </button>
              </div>
            </div>

            <div v-if="validationMsg" class="alert alert-warning mt-3 py-2 mb-0">
              {{ validationMsg }}
            </div>
          </form>
        </div>
      </div>

      <!-- ROW 1: BAR (rentang) -->
      <div class="card h-100 shadow-sm mb-4">
        <div class="card-header fw-bold d-flex align-items-center justify-content-between flex-wrap gap-2">
          <span>
            Kunjungan Per Hari ({{ filters.start_date }} → {{ filters.end_date }}) •
            <small class="text-muted">{{ perDay.length }} hari</small>
          </span>
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

      <!-- ROW 2: DONUTS -->
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

      <!-- ROW 3: TOP 10 -->
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

const page = usePage()
const initial = page.props

// ====== FILTER: Rentang tanggal ======
const today = (initial.serverNow ?? new Date().toISOString().slice(0,10))
const defaultStart = initial.filters?.start_date ?? today
const defaultEnd   = initial.filters?.end_date   ?? today

const filters = ref({
  start_date: defaultStart,
  end_date: defaultEnd,
})

const validationMsg = ref('')

const isRangeValid = computed(() => {
  const s = filters.value.start_date
  const e = filters.value.end_date
  return !!s && !!e && s <= e
})

// ====== DATA dari server (struktur dipertahankan) ======
const perDayAll    = ref(initial.perDayAll ?? [])
const gender       = ref(initial.gender ?? { male: 0, female: 0 })
const payment      = ref(initial.payment ?? { bpjs: 0, non_bpjs: 0 })
const visit        = ref(initial.visit ?? { baru: 0, lama: 0 })
const referral     = ref(initial.referral ?? { internal: 0, rujukan: 0 })
const topDiseases  = ref(initial.topDiseases ?? [])

// ====== Aksi: Tampilkan ======
function applyFilter () {
  validationMsg.value = ''
  if (!isRangeValid.value) {
    validationMsg.value = 'Tanggal awal tidak boleh lebih besar dari tanggal akhir.'
    return
  }
  const s = filters.value.start_date
  const e = filters.value.end_date

  router.get(route('home.home'), { start_date: s, end_date: e }, {
    preserveScroll: true,
    preserveState: true,
    replace: true,
    onSuccess: () => {
      const p = usePage().props

      if (p.filters?.start_date) filters.value.start_date = p.filters.start_date
      if (p.filters?.end_date)   filters.value.end_date   = p.filters.end_date

      perDayAll.value   = p.perDayAll ?? []
      gender.value      = p.gender ?? { male: 0, female: 0 }
      payment.value     = p.payment ?? { bpjs: 0, non_bpjs: 0 }
      visit.value       = p.visit ?? { baru: 0, lama: 0 }
      referral.value    = p.referral ?? { internal: 0, rujukan: 0 }
      topDiseases.value = p.topDiseases ?? []

      refreshBar()
      updateDonuts()
    }
  })
}

function resetFilter () {
  filters.value.start_date = defaultStart
  filters.value.end_date   = defaultEnd
  applyFilter()
}

// Data utk bar (berdasarkan rentang)
const perDay = computed(() => perDayAll.value)

// Totals (badge)
const totalMaleRange   = computed(() => perDay.value.reduce((s, d) => s + (d.male || 0), 0))
const totalFemaleRange = computed(() => perDay.value.reduce((s, d) => s + (d.female || 0), 0))

// ====== Chart stuff ======
const barRef = ref(null)
const donutGenderRef = ref(null)
const donutPaymentRef = ref(null)
const donutVisitRef = ref(null)
const donutReferralRef = ref(null)
let ChartLib = null
let barChart = null

// Auto skala batang saat banyak hari
function dynamicBarSizing(count) {
  const barPct = Math.max(0.15, Math.min(0.85, 12 / Math.max(1, count)))
  const catPct = Math.max(0.4,  Math.min(0.9,  18 / Math.max(1, count)))
  const maxTicks = Math.min(14, Math.max(6, Math.floor(120 / Math.log2(Math.max(4, count)))))
  return { barPct, catPct, maxTicks }
}

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
  const yellow = '#FFD700' // kuning
  const grid = '#e9ecef'
  const border = '#dee2e6'

  const n = perDay.value.length
  const { barPct, catPct, maxTicks } = dynamicBarSizing(n)

  return {
    type: 'bar',
    data: {
      labels: perDay.value.map(d => d.date),
      datasets: [
        {
          label: 'Laki-laki',
          data: perDay.value.map(d => d.male),
          backgroundColor: blue,
          borderWidth: 0,
          barPercentage: barPct,
          categoryPercentage: catPct,
          stack: 'v'
        },
        {
          label: 'Perempuan',
          data: perDay.value.map(d => d.female),
          backgroundColor: yellow, // <-- ganti ke kuning
          borderWidth: 0,
          barPercentage: barPct,
          categoryPercentage: catPct,
          stack: 'v',
          borderRadius: { topLeft: 8, topRight: 8 }
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      layout: { padding: { left: 0, right: 0, top: 8, bottom: 8 } },
      interaction: { mode: 'index', intersect: false },
      scales: {
        x: {
          stacked: true,
          offset: true,
          grid: { color: grid, drawBorder: true, borderColor: border },
          ticks: {
            autoSkip: true,
            maxTicksLimit: maxTicks,
            maxRotation: 0,
            minRotation: 0,
            // pakai function biasa, jangan arrow
            callback: function (value, index) {
              const step = Math.max(1, Math.ceil(n / maxTicks))
              return (index % step === 0) ? (perDay.value[index]?.date ?? '') : ''
            }
          }
        },
        y: {
          stacked: true,
          beginAtZero: true,
          grace: '10%',
          grid: { color: grid, drawBorder: true, borderColor: border },
          ticks: { precision: 0 }
        },
      },
      plugins: {
        legend: { position: 'top', labels: { boxWidth: 14, boxHeight: 14 } },
        tooltip: { callbacks: { footer: (items) => 'Total: ' + items.reduce((s, i) => s + i.parsed.y, 0) } },
      },
      animation: { duration: 250 },
    }
  }
}


function refreshBar() {
  if (!ChartLib || !barRef.value) return
  if (barChart) {
    barChart.destroy()
    barChart = null
  }
  barChart = new ChartLib(barRef.value, buildBarConfig())
}

onMounted(async () => {
  ChartLib = (await import('chart.js/auto')).default
  ChartLib.register(centerTextPlugin)

  // render pertama
  refreshBar()

  const commonOpts = (formatter) => ({
    responsive: true,
    maintainAspectRatio: false,
    cutout: '60%',
    plugins: { legend: { position: 'bottom' }, centerText: { formatter } }
  })

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

// Re-render bar kalau array berubah referensi
watch(perDay, () => { refreshBar() })
</script>

<style scoped>
.chart-wrap {
  position: relative;
  width: 100%;
  height: 520px;
}
.chart-wrap canvas,
.chart-donut canvas {
  display: block;
  width: 100% !important;
  height: 100% !important;
}
</style>
