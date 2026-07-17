<template>
  <AppLayouts>
    <div class="ptm-laporan-wrapper">

      <!-- ── Page Header ── -->
      <div class="page-header">
        <div class="header-left">
          <div class="header-icon">
            <i class="bi bi-heart-pulse-fill"></i>
          </div>
          <div>
            <h2 class="page-title">Laporan Skrining PTM</h2>
            <p class="page-subtitle">Penyakit Tidak Menular &mdash; Data Pemeriksaan &amp; Hasil Skrining</p>
          </div>
        </div>
        <div class="header-right">
          <span class="badge-puskesmas">
            <i class="bi bi-hospital"></i> PUSKESMAS WONGSOREJO
          </span>
        </div>
      </div>

      <!-- ── Filter Card ── -->
      <div class="filter-card">
        <div class="filter-card-header">
          <i class="bi bi-funnel-fill"></i>
          <span>Filter Data Laporan</span>
        </div>
        <div class="filter-card-body">
          <div class="filter-grid">



            <!-- Tanggal Awal -->
            <div class="filter-field">
              <label class="filter-label">Tanggal Awal</label>
              <input v-model="tglAwal" type="date" class="filter-input" />
            </div>

            <!-- Tanggal Akhir -->
            <div class="filter-field">
              <label class="filter-label">Tanggal Akhir</label>
              <input v-model="tglAkhir" type="date" class="filter-input" />
            </div>

            <!-- Jenis Kelamin -->
            <div class="filter-field">
              <label class="filter-label">Jenis Kelamin</label>
              <select v-model="selectedJK" class="filter-select">
                <option value="">— Semua —</option>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
              </select>
            </div>

            <!-- Usia -->
            <div class="filter-field">
              <label class="filter-label">Kelompok Usia</label>
              <select v-model="selectedUsia" class="filter-select">
                <option value="">— Semua Usia —</option>
                <option value="15-19">15 – 19 Tahun</option>
                <option value="20-44">20 – 44 Tahun</option>
                <option value="45-59">45 – 59 Tahun</option>
                <option value="60+">≥ 60 Tahun</option>
              </select>
            </div>

          </div>

          <!-- Action Buttons -->
          <div class="filter-actions">
            <button
              type="button"
              class="btn-action btn-tampil"
              :class="{ 'btn-loading': isLoading }"
              :disabled="isLoading"
              @click="handleTampilkan"
            >
              <i :class="isLoading ? 'bi bi-arrow-repeat spin' : 'bi bi-search'"></i>
              {{ isLoading ? 'Memuat...' : 'Tampilkan Data' }}
            </button>
            <button type="button" class="btn-action btn-excel" @click="handleDownloadExcel">
              <i class="bi bi-file-earmark-excel-fill"></i>
              Download Excel
            </button>
            <button type="button" class="btn-action btn-pdf" @click="handleDownloadPDF">
              <i class="bi bi-file-earmark-pdf-fill"></i>
              Download PDF
            </button>
            <button type="button" class="btn-action btn-reset" @click="handleReset">
              <i class="bi bi-arrow-counterclockwise"></i>
              Reset
            </button>
          </div>
        </div>
      </div>

      <!-- ── Statistik Cards (tampil setelah filter) ── -->
      <div v-if="dataTampil" class="stats-grid">
        <div class="stat-card stat-total">
          <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
          <div class="stat-info">
            <span class="stat-value">{{ statistik.total_skrining.toLocaleString('id-ID') }}</span>
            <span class="stat-label">Total Skrining</span>
          </div>
        </div>
        <div class="stat-card stat-hipertensi">
          <div class="stat-icon"><i class="bi bi-activity"></i></div>
          <div class="stat-info">
            <span class="stat-value">{{ statistik.total_hipertensi.toLocaleString('id-ID') }}</span>
            <span class="stat-label">Hipertensi</span>
          </div>
        </div>
        <div class="stat-card stat-diabetes">
          <div class="stat-icon"><i class="bi bi-droplet-fill"></i></div>
          <div class="stat-info">
            <span class="stat-value">{{ statistik.total_diabetes.toLocaleString('id-ID') }}</span>
            <span class="stat-label">Diabetes Melitus</span>
          </div>
        </div>
        <div class="stat-card stat-obesitas">
          <div class="stat-icon"><i class="bi bi-person-fill-exclamation"></i></div>
          <div class="stat-info">
            <span class="stat-value">{{ statistik.total_obesitas.toLocaleString('id-ID') }}</span>
            <span class="stat-label">Obesitas</span>
          </div>
        </div>
        <div class="stat-card stat-normal">
          <div class="stat-icon"><i class="bi bi-check-circle-fill"></i></div>
          <div class="stat-info">
            <span class="stat-value">{{ statistik.total_asam_urat.toLocaleString('id-ID') }}</span>
            <span class="stat-label">Asam Urat</span>
          </div>
        </div>
      </div>

      <!-- ── Tabel Data ── -->
      <div v-if="dataTampil" class="table-card">
        <div class="table-card-header">
          <div class="table-header-left">
            <h4><i class="bi bi-table"></i> Data Hasil Skrining PTM</h4>
            <span class="data-period">Periode: {{ tglAwal || '—' }} s/d {{ tglAkhir || '—' }}</span>
          </div>
          <div class="table-header-right">
            <div class="search-box">
              <i class="bi bi-search"></i>
              <input v-model="searchQuery" type="text" placeholder="Cari nama / NIK..." />
            </div>
            <span class="row-count">{{ filteredData.length }} data</span>
          </div>
        </div>

        <div class="table-wrapper">
          <table class="data-table">
            <thead>
              <tr>
                <th class="col-no">No</th>
                <th class="col-tgl">Tgl Skrining</th>
                <th class="col-nik">NIK</th>
                <th class="col-nama">Nama Pasien</th>
                <th class="col-jk">JK</th>
                <th class="col-usia">Usia</th>
                <th class="col-td">Tekanan Darah</th>
                <th class="col-imt">IMT</th>
                <th class="col-gds">GDS</th>
                <th class="col-diagnosa">Diagnosa</th>
                <th class="col-status">Status</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(row, index) in filteredData" :key="index" class="data-row">
                <td class="col-no">{{ index + 1 }}</td>
                <td class="col-tgl">{{ formatDate(row.tglKunjungan) }}</td>
                <td class="col-nik">{{ row.NIK || '-' }}</td>
                <td class="col-nama">{{ row.NAMA_LGKP || '-' }}</td>
                <td class="col-jk">
                  <span :class="['badge-jk', getJKClass(row.jenis_klmin)]">
                    {{ formatJK(row.jenis_klmin) }}
                  </span>
                </td>
                <td class="col-usia">{{ row.umur ?? '-' }} th</td>
                <td class="col-td">
                  <span class="badge-td">{{ row.sistolik ?? '-' }}/{{ row.tekanan_diastolik ?? '-' }}</span>
                </td>
                <td class="col-imt">{{ row.imt ?? '-' }}</td>
                <td class="col-gds">{{ row.gds ?? '-' }}</td>
                <td class="col-diagnosa">{{ getStatusLabel(row) }}</td>
                <td class="col-status">
                  <span :class="['badge-status', getStatusClass(row)]">
                    {{ getStatusLabel(row) }}
                  </span>
                </td>
              </tr>

              <!-- Empty State -->
              <tr v-if="filteredData.length === 0">
                <td colspan="11" class="empty-state">
                  <i class="bi bi-inbox"></i>
                  <p>Tidak ada data yang sesuai filter.</p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- ── Empty State (sebelum filter) ── -->
      <div v-if="!dataTampil" class="empty-info">
        <div class="empty-info-icon">
          <i class="bi bi-heart-pulse"></i>
        </div>
        <h5>Silakan atur filter di atas</h5>
        <p>Tentukan rentang tanggal dan kriteria filter lainnya, kemudian klik <strong>Tampilkan Data</strong>.</p>
      </div>

    </div>
  </AppLayouts>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import AppLayouts from '@/Components/Layouts/AppLayouts.vue'

// ── Props dari Backend (Inertia) ──
const props = defineProps({
  DataLaporan:     { type: Array,   default: () => [] },
  Statistik:       { type: Object,  default: () => ({}) },
  DaftarKelurahan: { type: Array,   default: () => [] },
  dataTampil:      { type: Boolean, default: false },
  filters:         { type: Object,  default: () => ({}) },
})

// FIX: sebelumnya template memakai `dataTampil` (tanpa `props.`) padahal
// variabel ini tidak pernah dideklarasikan di script, sehingga statistik
// & tabel tidak pernah tampil. Sekarang dibuat computed agar template
// yang memakai `dataTampil` maupun `props.dataTampil` tetap konsisten.
const dataTampil = computed(() => props.dataTampil)

// ── Filter State (diinisialisasi dari props.filters agar persistent) ──
const tglAwal      = ref(props.filters?.tgl_awal      ?? '')
const tglAkhir     = ref(props.filters?.tgl_akhir     ?? '')
const selectedJK   = ref(props.filters?.jenis_kelamin ?? '')
const selectedUsia = ref(props.filters?.kelompok_usia ?? '')
const selectedDesa = ref(props.filters?.no_kel        ?? '')
const searchQuery  = ref('')
const isLoading    = ref(false)

// ── Computed: data yang ditampilkan (filter search lokal) ──
const filteredData = computed(() => {
  const q = searchQuery.value.toLowerCase().trim()
  if (!q) return props.DataLaporan

  // FIX: NO_MR bisa berupa number dari database, .toLowerCase() akan
  // error kalau dipanggil langsung pada number. Bungkus dengan String().
  return props.DataLaporan.filter(r =>
    String(r.NAMA_LGKP ?? '').toLowerCase().includes(q) ||
    String(r.NIK ?? '').includes(q) ||
    String(r.NO_MR ?? '').toLowerCase().includes(q)
  )
})

// ── Statistik ringkasan ──
const statistik = computed(() => ({
  total_skrining:     props.Statistik?.total_skrining     ?? 0,
  total_hipertensi:   props.Statistik?.total_hipertensi   ?? 0,
  total_diabetes:     props.Statistik?.total_diabetes     ?? 0,
  total_obesitas:     props.Statistik?.total_obesitas     ?? 0,
  total_asam_urat:    props.Statistik?.total_asam_urat    ?? 0,
  total_profil_lipid: props.Statistik?.total_profil_lipid ?? 0,
}))

// ── Helpers ──
const formatDate = (d) => {
  if (!d) return '-'
  try {
    return new Date(d).toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' })
  } catch {
    return d
  }
}

const formatJK = (jenis_klmin) => {
  if (jenis_klmin == 1) return 'L'
  if (jenis_klmin == 2) return 'P'
  return '-'
}

const getJKClass = (jenis_klmin) =>
  jenis_klmin == 1 ? 'badge-laki' : 'badge-perempuan'

// FIX: field ini sekarang cocok dengan alias yang di-select LaporanPTMService:
//   status_hipertensi, status_diabetes, kategori_obesitas
const getStatusClass = (row) => {
  if (row.status_hipertensi && row.status_hipertensi !== 'Normal') return 'status-tinggi'
  if (row.status_diabetes   && row.status_diabetes   !== 'Normal') return 'status-tinggi'
  if (row.kategori_obesitas && row.kategori_obesitas !== 'Normal') return 'status-sedang'
  return 'status-normal'
}

const getStatusLabel = (row) => {
  const masalah = []
  if (row.status_hipertensi && row.status_hipertensi !== 'Normal') masalah.push(row.status_hipertensi)
  if (row.status_diabetes   && row.status_diabetes   !== 'Normal') masalah.push(row.status_diabetes)
  if (row.kategori_obesitas && row.kategori_obesitas !== 'Normal') masalah.push(row.kategori_obesitas)
  return masalah.length ? masalah.join(', ') : 'Normal'
}

// ── Actions ──
const handleTampilkan = () => {
  if (!tglAwal.value || !tglAkhir.value) {
    alert('Silakan isi tanggal awal dan tanggal akhir!')
    return
  }

  isLoading.value = true

  router.get(
    route('ruang-layanan.laporan-ptm'),
    {
      tgl_awal:      tglAwal.value,
      tgl_akhir:     tglAkhir.value,
      jenis_kelamin: selectedJK.value   || undefined,
      kelompok_usia: selectedUsia.value || undefined,
      no_kel:        selectedDesa.value || undefined,
    },
    {
      preserveState:  true,
      preserveScroll: true,
      replace:        true,
      onFinish: () => { isLoading.value = false },
    }
  )
}

const handleReset = () => {
  tglAwal.value      = ''
  tglAkhir.value     = ''
  selectedJK.value   = ''
  selectedUsia.value = ''
  selectedDesa.value = ''
  searchQuery.value  = ''

  router.get(
    route('ruang-layanan.laporan-ptm'),
    {},
    { preserveState: false, replace: true }
  )
}

const handleDownloadExcel = () => {
  if (!dataTampil.value) {
    alert('Tampilkan data terlebih dahulu!')
    return
  }
  alert('Fitur download Excel akan segera tersedia.')
}

const handleDownloadPDF = () => {
  if (!dataTampil.value) {
    alert('Tampilkan data terlebih dahulu!')
    return
  }
  alert('Fitur download PDF akan segera tersedia.')
}
</script>

<style scoped>
/* ── Wrapper ── */
.ptm-laporan-wrapper {
  max-width: 1400px;
  margin: 0 auto;
  padding: 24px 20px;
  font-family: 'Segoe UI', system-ui, sans-serif;
}

/* ── Page Header ── */
.page-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  margin-bottom: 24px;
  padding: 20px 24px;
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  box-shadow: 0 4px 16px rgba(15, 23, 42, 0.06);
}

.header-left {
  display: flex;
  align-items: center;
  gap: 16px;
}

.header-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 52px;
  height: 52px;
  border-radius: 12px;
  background: linear-gradient(135deg, #0f766e, #10b981);
  color: #ffffff;
  font-size: 24px;
  flex-shrink: 0;
  box-shadow: 0 6px 16px rgba(15, 118, 110, 0.3);
}

.page-title {
  margin: 0;
  font-size: 1.35rem;
  font-weight: 800;
  color: #0f172a;
}

.page-subtitle {
  margin: 3px 0 0;
  font-size: 0.84rem;
  color: #64748b;
}

.badge-puskesmas {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 8px 14px;
  background: linear-gradient(135deg, #e0f2fe, #bae6fd);
  color: #0369a1;
  border-radius: 20px;
  font-size: 0.82rem;
  font-weight: 700;
  border: 1px solid #7dd3fc;
  white-space: nowrap;
}

/* ── Filter Card ── */
.filter-card {
  margin-bottom: 20px;
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  box-shadow: 0 4px 16px rgba(15, 23, 42, 0.06);
  overflow: hidden;
}

.filter-card-header {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 14px 20px;
  background: linear-gradient(135deg, #0f766e, #10b981);
  color: #ffffff;
  font-size: 0.95rem;
  font-weight: 700;
}

.filter-card-body {
  padding: 22px 24px;
}

.filter-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 16px;
  margin-bottom: 22px;
}

.filter-field {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.filter-label {
  font-size: 0.82rem;
  font-weight: 700;
  color: #374151;
  text-transform: uppercase;
  letter-spacing: 0.4px;
}

.filter-select,
.filter-input {
  width: 100%;
  min-height: 40px;
  padding: 8px 12px;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-size: 0.88rem;
  color: #0f172a;
  background: #f9fafb;
  transition: border-color 0.2s, box-shadow 0.2s;
  outline: none;
}

.filter-select:focus,
.filter-input:focus {
  border-color: #0f766e;
  box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.12);
  background: #ffffff;
}

/* ── Filter Actions ── */
.filter-actions {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 10px;
  padding-top: 18px;
  border-top: 1px solid #f1f5f9;
}

.btn-action {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  padding: 9px 18px;
  border: none;
  border-radius: 8px;
  font-size: 0.87rem;
  font-weight: 700;
  cursor: pointer;
  transition: opacity 0.18s, transform 0.15s, box-shadow 0.18s;
}

.btn-action:hover {
  opacity: 0.9;
  transform: translateY(-1px);
}

.btn-action:active {
  transform: translateY(0);
}

.btn-tampil {
  background: linear-gradient(135deg, #0f766e, #10b981);
  color: #ffffff;
  box-shadow: 0 4px 14px rgba(15, 118, 110, 0.28);
}

.btn-tampil:disabled,
.btn-loading {
  opacity: 0.75;
  cursor: not-allowed;
  transform: none !important;
}

.spin {
  display: inline-block;
  animation: spin 0.8s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.btn-excel {
  background: linear-gradient(135deg, #15803d, #22c55e);
  color: #ffffff;
  box-shadow: 0 4px 14px rgba(21, 128, 61, 0.25);
}

.btn-pdf {
  background: linear-gradient(135deg, #dc2626, #ef4444);
  color: #ffffff;
  box-shadow: 0 4px 14px rgba(220, 38, 38, 0.25);
}

.btn-reset {
  background: #f1f5f9;
  color: #475569;
  border: 1px solid #e2e8f0;
  box-shadow: none;
}

.btn-reset:hover {
  background: #e2e8f0;
}

/* ── Stats Grid ── */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 14px;
  margin-bottom: 20px;
}

.stat-card {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 16px 18px;
  border-radius: 12px;
  background: #ffffff;
  border: 1px solid #e2e8f0;
  box-shadow: 0 4px 14px rgba(15, 23, 42, 0.06);
  transition: transform 0.2s;
}

.stat-card:hover {
  transform: translateY(-2px);
}

.stat-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 46px;
  height: 46px;
  border-radius: 10px;
  font-size: 22px;
  flex-shrink: 0;
}

.stat-info {
  display: flex;
  flex-direction: column;
}

.stat-value {
  font-size: 1.5rem;
  font-weight: 800;
  line-height: 1.1;
}

.stat-label {
  font-size: 0.75rem;
  font-weight: 600;
  color: #64748b;
  margin-top: 3px;
}

/* Stat variants */
.stat-total      .stat-icon { background: #eff6ff; color: #2563eb; }
.stat-total      .stat-value { color: #1d4ed8; }

.stat-hipertensi .stat-icon { background: #fff1f2; color: #e11d48; }
.stat-hipertensi .stat-value { color: #be123c; }

.stat-diabetes   .stat-icon { background: #fffbeb; color: #d97706; }
.stat-diabetes   .stat-value { color: #b45309; }

.stat-obesitas   .stat-icon { background: #fdf4ff; color: #9333ea; }
.stat-obesitas   .stat-value { color: #7e22ce; }

.stat-normal     .stat-icon { background: #f0fdf4; color: #16a34a; }
.stat-normal     .stat-value { color: #15803d; }

/* ── Table Card ── */
.table-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  box-shadow: 0 4px 16px rgba(15, 23, 42, 0.06);
  overflow: hidden;
}

.table-card-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 14px;
  padding: 16px 20px;
  background: #f8fafc;
  border-bottom: 1px solid #e2e8f0;
}

.table-header-left h4 {
  margin: 0;
  font-size: 0.95rem;
  font-weight: 800;
  color: #0f172a;
  display: flex;
  align-items: center;
  gap: 8px;
}

.data-period {
  font-size: 0.78rem;
  color: #64748b;
  margin-top: 3px;
  display: block;
}

.table-header-right {
  display: flex;
  align-items: center;
  gap: 12px;
}

.search-box {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 7px 12px;
  background: #ffffff;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-size: 0.85rem;
  color: #9ca3af;
}

.search-box input {
  border: none;
  outline: none;
  font-size: 0.85rem;
  color: #0f172a;
  width: 180px;
  background: transparent;
}

.row-count {
  font-size: 0.78rem;
  font-weight: 700;
  color: #64748b;
  white-space: nowrap;
}

/* ── Data Table ── */
.table-wrapper {
  overflow-x: auto;
}

.data-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.86rem;
}

.data-table thead {
  background: #1e293b;
}

.data-table thead th {
  padding: 13px 12px;
  color: #e2e8f0;
  font-size: 0.75rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  text-align: left;
  white-space: nowrap;
  border: none;
}

.data-table tbody tr {
  border-bottom: 1px solid #f1f5f9;
  transition: background 0.15s;
}

.data-table tbody tr:nth-child(even) {
  background: #fafbfc;
}

.data-table tbody tr:hover {
  background: #f0fdf9;
}

.data-table td {
  padding: 11px 12px;
  color: #1e293b;
  vertical-align: middle;
  border: none;
}

/* Column Widths */
.col-no       { width: 44px; text-align: center; font-weight: 700; color: #64748b !important; }
.col-tgl      { width: 100px; white-space: nowrap; }
.col-nik      { width: 170px; font-size: 0.82rem; color: #475569 !important; }
.col-nama     { min-width: 160px; font-weight: 600; }
.col-jk       { width: 50px; text-align: center; }
.col-usia     { width: 60px; text-align: center; }
.col-td       { width: 100px; text-align: center; }
.col-imt      { width: 64px; text-align: center; }
.col-gds      { width: 64px; text-align: center; }
.col-diagnosa { min-width: 160px; }
.col-status   { width: 120px; }

/* Badges */
.badge-jk {
  display: inline-block;
  padding: 3px 10px;
  border-radius: 999px;
  font-size: 0.75rem;
  font-weight: 800;
}

.badge-laki {
  background: #dbeafe;
  color: #1d4ed8;
}

.badge-perempuan {
  background: #fce7f3;
  color: #9d174d;
}

.badge-td {
  display: inline-block;
  padding: 4px 10px;
  border-radius: 999px;
  background: #0ea5e9;
  color: #ffffff;
  font-size: 0.8rem;
  font-weight: 700;
}

.badge-status {
  display: inline-block;
  padding: 4px 10px;
  border-radius: 6px;
  font-size: 0.75rem;
  font-weight: 700;
}

.status-tinggi {
  background: #fee2e2;
  color: #dc2626;
}

.status-sedang {
  background: #fef3c7;
  color: #b45309;
}

.status-normal {
  background: #dcfce7;
  color: #15803d;
}

/* ── Empty State (table) ── */
.empty-state {
  padding: 48px 0;
  text-align: center;
  color: #94a3b8;
}

.empty-state i {
  font-size: 2.5rem;
  display: block;
  margin-bottom: 8px;
}

.empty-state p {
  font-size: 0.9rem;
  font-style: italic;
}

/* ── Empty Info (sebelum filter) ── */
.empty-info {
  margin-top: 16px;
  padding: 64px 24px;
  text-align: center;
  background: #ffffff;
  border: 1px dashed #cbd5e1;
  border-radius: 12px;
  color: #94a3b8;
}

.empty-info-icon {
  font-size: 3.5rem;
  margin-bottom: 14px;
  color: #0f766e;
  opacity: 0.4;
}

.empty-info h5 {
  font-size: 1rem;
  font-weight: 700;
  color: #475569;
  margin-bottom: 6px;
}

.empty-info p {
  font-size: 0.87rem;
  color: #94a3b8;
}

/* ── Responsive ── */
@media (max-width: 1024px) {
  .stats-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}

@media (max-width: 768px) {
  .ptm-laporan-wrapper {
    padding: 16px 12px;
  }

  .filter-grid {
    grid-template-columns: 1fr;
  }

  .page-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 12px;
  }

  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }

  .table-card-header {
    flex-direction: column;
    align-items: flex-start;
  }

  .search-box input {
    width: 140px;
  }
}

@media (max-width: 480px) {
  .stats-grid {
    grid-template-columns: 1fr;
  }

  .filter-actions {
    flex-direction: column;
    align-items: stretch;
  }

  .btn-action {
    justify-content: center;
  }
}
</style>