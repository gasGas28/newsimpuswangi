<template>
  <AppLayout title="Home">
    <div class="db">

      <!-- TOPBAR -->
      <div class="topbar">
        <div>
          <div class="topbar-title">Dashboard Kunjungan</div>
          <div class="topbar-sub">Rawat Jalan · Data per hari</div>
        </div>
        <div class="topbar-date">Periode: {{ filters.start_date }} — {{ filters.end_date }}</div>
      </div>

      <!-- FILTER -->
      <div class="filter-row">
        <label class="filter-label">Periode</label>
        <input type="date" class="date-input" v-model="filters.start_date" />
        <span class="sep">—</span>
        <input type="date" class="date-input" v-model="filters.end_date" />
        <button class="btn-apply" :disabled="!isRangeValid" @click="applyFilter">
          <i class="bi bi-eye"></i> Tampilkan
        </button>
        <button class="btn-reset" @click="resetFilter">Reset</button>
        <span v-if="validationMsg" class="filter-error">{{ validationMsg }}</span>
      </div>

      <!-- METRIC CARDS -->
      <div class="metrics">
        <div class="metric-card">
          <div class="metric-label">Total Kunjungan</div>
          <div class="metric-value">{{ totalVisit.toLocaleString('id-ID') }}</div>
          <div class="metric-sub">dalam periode ini</div>
        </div>
        <div class="metric-card">
          <div class="metric-label">Laki-laki</div>
          <div class="metric-value blue">{{ gender.male.toLocaleString('id-ID') }}</div>
          <div class="metric-sub">{{ pct(gender.male, totalVisit) }}%</div>
        </div>
        <div class="metric-card">
          <div class="metric-label">Perempuan</div>
          <div class="metric-value pink">{{ gender.female.toLocaleString('id-ID') }}</div>
          <div class="metric-sub">{{ pct(gender.female, totalVisit) }}%</div>
        </div>
        <div class="metric-card">
          <div class="metric-label">BPJS</div>
          <div class="metric-value green">{{ payment.bpjs.toLocaleString('id-ID') }}</div>
          <div class="metric-sub">{{ pct(payment.bpjs, totalVisit) }}%</div>
        </div>
        <div class="metric-card">
          <div class="metric-label">Pasien Baru</div>
          <div class="metric-value amber">{{ visit.baru.toLocaleString('id-ID') }}</div>
          <div class="metric-sub">{{ pct(visit.baru, totalVisit) }}%</div>
        </div>
      </div>

      <!-- BAR CHART -->
      <div class="section">
        <div class="section-label">Kunjungan per hari</div>
        <div class="chart-box">
          <div class="legend-row mb-2">
            <div class="legend-item">
              <div class="legend-dot" style="background:#378ADD"></div>
              Laki-laki
              <strong class="ms-1">{{ totalMaleRange }}</strong>
            </div>
            <div class="legend-item">
              <div class="legend-dot" style="background:#D4537E"></div>
              Perempuan
              <strong class="ms-1">{{ totalFemaleRange }}</strong>
            </div>
          </div>
          <div class="chart-wrap">
            <canvas ref="barRef"></canvas>
          </div>
        </div>
      </div>

      <!-- DONUT CHARTS -->
      <div class="donuts-grid">

        <div class="donut-card">
          <div class="donut-title">Jenis Kelamin</div>
          <div class="donut-wrap">
            <canvas ref="donutGenderRef"></canvas>
          </div>
          <div class="legend-row">
            <div class="legend-item">
              <div class="legend-dot" style="background:#378ADD"></div>
              Laki-laki <strong class="ms-1">{{ gender.male }}</strong>
            </div>
            <div class="legend-item">
              <div class="legend-dot" style="background:#D4537E"></div>
              Perempuan <strong class="ms-1">{{ gender.female }}</strong>
            </div>
          </div>
        </div>

        <div class="donut-card">
          <div class="donut-title">Pembiayaan</div>
          <div class="donut-wrap">
            <canvas ref="donutPaymentRef"></canvas>
          </div>
          <div class="legend-row">
            <div class="legend-item">
              <div class="legend-dot" style="background:#3B6D11"></div>
              BPJS <strong class="ms-1">{{ payment.bpjs }}</strong>
            </div>
            <div class="legend-item">
              <div class="legend-dot" style="background:#888780"></div>
              Non-BPJS <strong class="ms-1">{{ payment.non_bpjs }}</strong>
            </div>
          </div>
        </div>

        <div class="donut-card">
          <div class="donut-title">Tipe Kunjungan</div>
          <div class="donut-wrap">
            <canvas ref="donutVisitRef"></canvas>
          </div>
          <div class="legend-row">
            <div class="legend-item">
              <div class="legend-dot" style="background:#185FA5"></div>
              Baru <strong class="ms-1">{{ visit.baru }}</strong>
            </div>
            <div class="legend-item">
              <div class="legend-dot" style="background:#5DCAA5"></div>
              Lama <strong class="ms-1">{{ visit.lama }}</strong>
            </div>
          </div>
        </div>

        <div class="donut-card">
          <div class="donut-title">Status Rujukan</div>
          <div class="donut-wrap">
            <canvas ref="donutReferralRef"></canvas>
          </div>
          <div class="legend-row">
            <div class="legend-item">
              <div class="legend-dot" style="background:#534AB7"></div>
              Internal <strong class="ms-1">{{ referral.internal }}</strong>
            </div>
            <div class="legend-item">
              <div class="legend-dot" style="background:#EF9F27"></div>
              Rujukan <strong class="ms-1">{{ referral.rujukan }}</strong>
            </div>
          </div>
        </div>

      </div>

      <!-- TOP 10 PENYAKIT -->
      <div class="section">
        <div class="section-label">10 Penyakit Terbesar</div>
        <div class="table-box">
          <table>
            <thead>
              <tr>
                <th style="width:36px;">#</th>
                <th>Kode</th>
                <th>Nama Penyakit</th>
                <th class="text-right">Total</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(row, idx) in topDiseases" :key="idx">
                <td class="rank-num">{{ idx + 1 }}</td>
                <td><span class="code-badge">{{ row.kode }}</span></td>
                <td>
                  <div class="dis-name">{{ row.nama }}</div>
                  <div class="bar-mini" :style="{ width: barWidth(row.total) + '%' }"></div>
                </td>
                <td class="dis-total">{{ row.total.toLocaleString('id-ID') }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import AppLayout from '@/Components/Layouts/AppLayouts.vue'

const page    = usePage()
const initial = page.props

// ── Filter ──────────────────────────────────────────────────────────
const today        = initial.serverNow ?? new Date().toISOString().slice(0, 10)
const defaultStart = initial.filters?.start_date ?? today
const defaultEnd   = initial.filters?.end_date   ?? today

const filters       = ref({ start_date: defaultStart, end_date: defaultEnd })
const validationMsg = ref('')

const isRangeValid = computed(() => {
  const { start_date: s, end_date: e } = filters.value
  return !!s && !!e && s <= e
})

// ── Server data ──────────────────────────────────────────────────────
const perDayAll   = ref(initial.perDayAll   ?? [])
const gender      = ref(initial.gender      ?? { male: 0, female: 0 })
const payment     = ref(initial.payment     ?? { bpjs: 0, non_bpjs: 0 })
const visit       = ref(initial.visit       ?? { baru: 0, lama: 0 })
const referral    = ref(initial.referral    ?? { internal: 0, rujukan: 0 })
const topDiseases = ref(initial.topDiseases ?? [])

// ── Computed ─────────────────────────────────────────────────────────
const perDay          = computed(() => perDayAll.value)
const totalMaleRange  = computed(() => perDay.value.reduce((s, d) => s + (d.male   || 0), 0))
const totalFemaleRange= computed(() => perDay.value.reduce((s, d) => s + (d.female || 0), 0))
const totalVisit      = computed(() => gender.value.male + gender.value.female)

const maxDiseaseTotal = computed(() =>
  topDiseases.value.length ? Math.max(...topDiseases.value.map(r => r.total)) : 1
)

function pct(val, total) {
  if (!total) return '0,0'
  return ((val / total) * 100).toFixed(1).replace('.', ',')
}

function barWidth(total) {
  return Math.round((total / maxDiseaseTotal.value) * 100)
}

// ── Filter actions ───────────────────────────────────────────────────
function applyFilter() {
  validationMsg.value = ''
  if (!isRangeValid.value) {
    validationMsg.value = 'Tanggal awal tidak boleh lebih besar dari tanggal akhir.'
    return
  }
  const { start_date: s, end_date: e } = filters.value
  router.get(route('home.home'), { start_date: s, end_date: e }, {
    preserveScroll: true,
    preserveState: true,
    replace: true,
    onSuccess: () => {
      const p = usePage().props
      if (p.filters?.start_date) filters.value.start_date = p.filters.start_date
      if (p.filters?.end_date)   filters.value.end_date   = p.filters.end_date
      perDayAll.value   = p.perDayAll   ?? []
      gender.value      = p.gender      ?? { male: 0, female: 0 }
      payment.value     = p.payment     ?? { bpjs: 0, non_bpjs: 0 }
      visit.value       = p.visit       ?? { baru: 0, lama: 0 }
      referral.value    = p.referral    ?? { internal: 0, rujukan: 0 }
      topDiseases.value = p.topDiseases ?? []
      refreshBar()
      updateDonuts()
    }
  })
}

function resetFilter() {
  filters.value.start_date = defaultStart
  filters.value.end_date   = defaultEnd
  applyFilter()
}

// ── Chart refs ───────────────────────────────────────────────────────
const barRef          = ref(null)
const donutGenderRef  = ref(null)
const donutPaymentRef = ref(null)
const donutVisitRef   = ref(null)
const donutReferralRef= ref(null)

let ChartLib         = null
let barChart         = null
let donutGenderChart = null
let donutPaymentChart= null
let donutVisitChart  = null
let donutReferralChart = null

// ── Bar chart ────────────────────────────────────────────────────────
function dynamicBarSizing(count) {
  const barPct  = Math.max(0.15, Math.min(0.85, 12 / Math.max(1, count)))
  const catPct  = Math.max(0.4,  Math.min(0.9,  18 / Math.max(1, count)))
  const maxTicks= Math.min(14, Math.max(6, Math.floor(120 / Math.log2(Math.max(4, count)))))
  return { barPct, catPct, maxTicks }
}

function buildBarConfig() {
  const n = perDay.value.length
  const { barPct, catPct, maxTicks } = dynamicBarSizing(n)
  const gridColor = 'rgba(0,0,0,0.06)'
  const tickColor = 'rgba(0,0,0,0.45)'

  return {
    type: 'bar',
    data: {
      labels: perDay.value.map(d => d.date),
      datasets: [
        {
          label: 'Laki-laki',
          data: perDay.value.map(d => d.male),
          backgroundColor: '#378ADD',
          borderWidth: 0,
          barPercentage: barPct,
          categoryPercentage: catPct,
          stack: 'v'
        },
        {
          label: 'Perempuan',
          data: perDay.value.map(d => d.female),
          backgroundColor: '#D4537E',
          borderWidth: 0,
          barPercentage: barPct,
          categoryPercentage: catPct,
          stack: 'v',
          borderRadius: { topLeft: 4, topRight: 4 }
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      interaction: { mode: 'index', intersect: false },
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            footer: (items) => 'Total: ' + items.reduce((s, i) => s + i.parsed.y, 0)
          }
        }
      },
      scales: {
        x: {
          stacked: true,
          grid: { color: gridColor },
          ticks: {
            color: tickColor,
            font: { size: 11, family: "'DM Sans', 'Segoe UI', sans-serif" },
            autoSkip: true,
            maxTicksLimit: maxTicks,
            maxRotation: 0,
            callback: function (value, index) {
              const step = Math.max(1, Math.ceil(n / maxTicks))
              return (index % step === 0) ? (perDay.value[index]?.date ?? '') : ''
            }
          }
        },
        y: {
          stacked: true,
          beginAtZero: true,
          grace: '8%',
          grid: { color: gridColor },
          ticks: {
            color: tickColor,
            font: { size: 11, family: "'DM Sans', 'Segoe UI', sans-serif" },
            precision: 0
          }
        }
      },
      animation: { duration: 300 }
    }
  }
}

function refreshBar() {
  if (!ChartLib || !barRef.value) return
  if (barChart) { barChart.destroy(); barChart = null }
  barChart = new ChartLib(barRef.value, buildBarConfig())
}

// ── Donut helpers ─────────────────────────────────────────────────────
function donutOpts() {
  return {
    responsive: true,
    maintainAspectRatio: false,
    cutout: '68%',
    plugins: {
      legend: { display: false },
      tooltip: { callbacks: { label: c => `${c.label}: ${c.parsed}` } }
    },
    animation: { duration: 300 }
  }
}

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

// ── Lifecycle ─────────────────────────────────────────────────────────
onMounted(async () => {
  ChartLib = (await import('chart.js/auto')).default

  refreshBar()

  donutGenderChart = new ChartLib(donutGenderRef.value, {
    type: 'doughnut',
    data: {
      labels: ['Laki-laki', 'Perempuan'],
      datasets: [{ data: [gender.value.male, gender.value.female], backgroundColor: ['#378ADD', '#D4537E'], borderWidth: 0 }]
    },
    options: donutOpts()
  })

  donutPaymentChart = new ChartLib(donutPaymentRef.value, {
    type: 'doughnut',
    data: {
      labels: ['BPJS', 'Non-BPJS'],
      datasets: [{ data: [payment.value.bpjs, payment.value.non_bpjs], backgroundColor: ['#3B6D11', '#888780'], borderWidth: 0 }]
    },
    options: donutOpts()
  })

  donutVisitChart = new ChartLib(donutVisitRef.value, {
    type: 'doughnut',
    data: {
      labels: ['Baru', 'Lama'],
      datasets: [{ data: [visit.value.baru, visit.value.lama], backgroundColor: ['#185FA5', '#5DCAA5'], borderWidth: 0 }]
    },
    options: donutOpts()
  })

  donutReferralChart = new ChartLib(donutReferralRef.value, {
    type: 'doughnut',
    data: {
      labels: ['Internal', 'Rujukan'],
      datasets: [{ data: [referral.value.internal, referral.value.rujukan], backgroundColor: ['#534AB7', '#EF9F27'], borderWidth: 0 }]
    },
    options: donutOpts()
  })
})

watch(perDay, () => refreshBar())
</script>

<style scoped>
/* ── Base ── */
.db {
  font-family: 'DM Sans', 'Segoe UI', sans-serif;
  color: #1a1a1a;
  padding-bottom: 2rem;
}

/* ── Topbar ── */
.topbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1.25rem 1.5rem 0.75rem;
  border-bottom: 1px solid #e9ecef;
  margin-bottom: 1rem;
}
.topbar-title { font-size: 18px; font-weight: 600; letter-spacing: -0.3px; }
.topbar-sub   { font-size: 13px; color: #6c757d; margin-top: 2px; }
.topbar-date  { font-size: 12px; color: #6c757d; }

/* ── Filter ── */
.filter-row {
  display: flex;
  align-items: center;
  gap: 10px;
  flex-wrap: wrap;
  padding: 0 1.5rem 1rem;
}
.filter-label { font-size: 13px; color: #6c757d; }
.date-input {
  font-size: 13px;
  padding: 6px 10px;
  border: 1px solid #dee2e6;
  border-radius: 8px;
  background: #fff;
  color: #212529;
  outline: none;
  transition: border-color .15s;
}
.date-input:focus { border-color: #378ADD; }
.sep { color: #adb5bd; font-size: 13px; }
.btn-apply {
  font-size: 13px;
  font-weight: 600;
  padding: 6px 16px;
  border-radius: 8px;
  border: none;
  background: #185FA5;
  color: #fff;
  cursor: pointer;
  transition: opacity .15s;
}
.btn-apply:disabled { opacity: .45; cursor: not-allowed; }
.btn-apply:not(:disabled):hover { opacity: .85; }
.btn-reset {
  font-size: 13px;
  padding: 6px 14px;
  border-radius: 8px;
  border: 1px solid #dee2e6;
  background: transparent;
  color: #6c757d;
  cursor: pointer;
  transition: background .15s;
}
.btn-reset:hover { background: #f8f9fa; }
.filter-error { font-size: 12px; color: #D4537E; }

/* ── Metric cards ── */
.metrics {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
  gap: 10px;
  padding: 0 1.5rem 1.25rem;
}
.metric-card {
  background: #f8f9fa;
  border-radius: 10px;
  padding: 14px 16px;
}
.metric-label {
  font-size: 11px;
  color: #6c757d;
  text-transform: uppercase;
  letter-spacing: .6px;
  margin-bottom: 6px;
}
.metric-value {
  font-size: 26px;
  font-weight: 600;
  letter-spacing: -1px;
  line-height: 1;
  color: #212529;
}
.metric-value.blue  { color: #185FA5; }
.metric-value.pink  { color: #D4537E; }
.metric-value.green { color: #3B6D11; }
.metric-value.amber { color: #BA7517; }
.metric-sub { font-size: 11px; color: #6c757d; margin-top: 4px; }

/* ── Section wrapper ── */
.section { padding: 0 1.5rem 1.5rem; }
.section-label {
  font-size: 11px;
  text-transform: uppercase;
  letter-spacing: .7px;
  color: #6c757d;
  font-weight: 600;
  margin-bottom: 10px;
}

/* ── Chart box ── */
.chart-box {
  background: #fff;
  border: 1px solid #e9ecef;
  border-radius: 12px;
  padding: 16px;
}
.chart-wrap {
  position: relative;
  width: 100%;
  height: 260px;
}
.chart-wrap canvas {
  display: block;
  width: 100% !important;
  height: 100% !important;
}

/* ── Donut grid ── */
.donuts-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 12px;
  padding: 0 1.5rem 1.5rem;
}
.donut-card {
  background: #fff;
  border: 1px solid #e9ecef;
  border-radius: 12px;
  padding: 16px;
}
.donut-title {
  font-size: 12px;
  font-weight: 600;
  color: #6c757d;
  text-transform: uppercase;
  letter-spacing: .5px;
  margin-bottom: 8px;
}
.donut-wrap {
  position: relative;
  height: 160px;
  width: 100%;
}
.donut-wrap canvas {
  display: block;
  width: 100% !important;
  height: 100% !important;
}

/* ── Legend ── */
.legend-row {
  display: flex;
  gap: 14px;
  flex-wrap: wrap;
  margin-top: 10px;
}
.legend-item {
  display: flex;
  align-items: center;
  gap: 5px;
  font-size: 12px;
  color: #6c757d;
}
.legend-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  flex-shrink: 0;
}

/* ── Disease table ── */
.table-box {
  background: #fff;
  border: 1px solid #e9ecef;
  border-radius: 12px;
  overflow: hidden;
}
.table-box table {
  width: 100%;
  border-collapse: collapse;
  font-size: 13px;
}
.table-box thead th {
  padding: 10px 14px;
  font-size: 11px;
  font-weight: 600;
  color: #6c757d;
  text-transform: uppercase;
  letter-spacing: .5px;
  text-align: left;
  border-bottom: 1px solid #e9ecef;
  background: #f8f9fa;
}
.table-box tbody td {
  padding: 9px 14px;
  border-bottom: 1px solid #f1f3f5;
  vertical-align: middle;
}
.table-box tbody tr:last-child td { border-bottom: none; }
.table-box tbody tr:hover td { background: #f8f9fa; }
.text-right { text-align: right !important; }
.rank-num { font-size: 11px; color: #adb5bd; width: 20px; }
.code-badge {
  font-size: 11px;
  font-family: 'Courier New', monospace;
  background: #e7f1fb;
  color: #185FA5;
  padding: 2px 7px;
  border-radius: 4px;
}
.dis-name  { color: #212529; }
.dis-total { text-align: right; font-weight: 600; color: #212529; }
.bar-mini {
  height: 3px;
  border-radius: 2px;
  background: #378ADD;
  opacity: .45;
  margin-top: 5px;
  transition: width .3s;
}
.mb-2 { margin-bottom: 8px; }
.ms-1 { margin-left: 4px; }
</style>