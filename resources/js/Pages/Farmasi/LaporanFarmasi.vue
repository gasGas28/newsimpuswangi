<script setup>
import { computed, reactive, ref, watch } from 'vue'
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Components/Layouts/AppLayouts.vue'

const localDate = (date = new Date()) => {
  const offset = date.getTimezoneOffset() * 60_000
  return new Date(date.getTime() - offset).toISOString().slice(0, 10)
}

const today = new Date()
const todayValue = localDate(today)
const formattedDate = new Intl.DateTimeFormat('id-ID', {
  weekday: 'long',
  day: 'numeric',
  month: 'long',
  year: 'numeric',
}).format(today)

const form = reactive({
  laporan: '',
  tanggalAwal: todayValue,
  tanggalAkhir: todayValue,
  unit: '',
  subUnit: '',
})

const unitOptions = [
  { value: 'SEMUA UNIT', label: 'Semua Unit' },
  { value: 'PUSKESMAS', label: 'Puskesmas' },
  { value: 'PUSTU', label: 'Pustu' },
  { value: 'POLINDES', label: 'Polindes' },
  { value: 'POSYANDU', label: 'Posyandu' },
  { value: 'POSKESDES', label: 'Poskesdes' },
  { value: 'PUSLING', label: 'Pusling' },
  { value: 'POSKESTREN', label: 'Poskestren' },
  { value: 'PONKESDES', label: 'Ponkesdes' },
]

const subUnitOptions = computed(() => {
  if (form.unit === 'PUSKESMAS') {
    return [
      { value: 'PUSKESMAS UTAMA', label: 'Puskesmas Utama' },
      { value: 'PUSKESMAS PENUNJANG', label: 'Puskesmas Penunjang' },
    ]
  }

  if (form.unit === 'PUSTU') {
    return [
      { value: 'PUSTU BARAT', label: 'Pustu Barat' },
      { value: 'PUSTU TIMUR', label: 'Pustu Timur' },
    ]
  }

  return []
})

const rows = ref([])
const hasSearched = ref(false)
const dateError = computed(() => form.tanggalAwal && form.tanggalAkhir && form.tanggalAwal > form.tanggalAkhir)
const activeFilterCount = computed(() => [form.laporan, form.unit, form.subUnit].filter(Boolean).length)

watch(() => form.unit, () => {
  form.subUnit = ''
})

const filterLaporan = () => {
  hasSearched.value = true
  rows.value = []

  if (!form.laporan || !form.tanggalAwal || !form.tanggalAkhir || dateError.value) return

  rows.value = [{
    id: 1,
    jenisLaporan: form.laporan,
    unit: form.unit || 'Semua Unit',
    subUnit: form.subUnit || '-',
    tanggal: `${form.tanggalAwal} s.d. ${form.tanggalAkhir}`,
    status: 'Siap ditinjau',
  }]
}

const resetFilter = () => {
  form.laporan = ''
  form.tanggalAwal = todayValue
  form.tanggalAkhir = todayValue
  form.unit = ''
  form.subUnit = ''
  rows.value = []
  hasSearched.value = false
}
</script>

<template>
  <AppLayout title="Laporan Farmasi">
    <main class="laporan-page">
      <div class="container py-4 py-lg-5">
        <section class="page-header" aria-labelledby="page-title">
          <div>
            <Link href="/farmasi" class="back-link"><i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali ke Farmasi</Link>
            <span class="eyebrow"><i class="bi bi-file-earmark-bar-graph" aria-hidden="true"></i> Modul pelaporan</span>
            <h1 id="page-title">Laporan Farmasi</h1>
            <p>Siapkan parameter laporan untuk meninjau aktivitas layanan farmasi.</p>
          </div>
          <div class="date-card"><i class="bi bi-calendar3" aria-hidden="true"></i><span>{{ formattedDate }}</span></div>
        </section>

        <section class="filter-card mb-4" aria-labelledby="filter-title">
          <div class="section-header">
            <div>
              <h2 id="filter-title"><i class="bi bi-sliders" aria-hidden="true"></i> Parameter Laporan</h2>
              <p>Pilih jenis laporan dan periode yang ingin ditinjau.</p>
            </div>
            <span v-if="activeFilterCount" class="filter-count">{{ activeFilterCount }} pilihan aktif</span>
          </div>

          <form @submit.prevent="filterLaporan">
            <div class="filter-grid">
              <div class="full-width">
                <label for="laporan" class="form-label">Jenis Laporan <span class="required">*</span></label>
                <select id="laporan" v-model="form.laporan" class="form-select" required>
                  <option value="">Pilih jenis laporan</option>
                  <option value="Laporan register pasien">Laporan register pasien</option>
                  <option value="Laporan jumlah pengeluaran langsung">Laporan jumlah pengeluaran langsung</option>
                  <option value="Laporan jumlah pemakaian harian">Laporan jumlah pemakaian harian</option>
                </select>
              </div>
              <div>
                <label for="unit" class="form-label">Unit</label>
                <select id="unit" v-model="form.unit" class="form-select">
                  <option value="">Semua unit</option>
                  <option v-for="item in unitOptions" :key="item.value" :value="item.value">{{ item.label }}</option>
                </select>
              </div>
              <div>
                <label for="sub-unit" class="form-label">Sub Unit</label>
                <select id="sub-unit" v-model="form.subUnit" class="form-select" :disabled="!subUnitOptions.length">
                  <option value="">{{ subUnitOptions.length ? 'Semua sub unit' : 'Pilih unit terlebih dahulu' }}</option>
                  <option v-for="item in subUnitOptions" :key="item.value" :value="item.value">{{ item.label }}</option>
                </select>
              </div>
              <div>
                <label for="tanggal-awal" class="form-label">Tanggal Awal <span class="required">*</span></label>
                <input id="tanggal-awal" v-model="form.tanggalAwal" type="date" class="form-control" :class="{ 'is-invalid': dateError }" required>
              </div>
              <div>
                <label for="tanggal-akhir" class="form-label">Tanggal Akhir <span class="required">*</span></label>
                <input id="tanggal-akhir" v-model="form.tanggalAkhir" type="date" class="form-control" :class="{ 'is-invalid': dateError }" required>
              </div>
            </div>
            <p v-if="dateError" class="date-error"><i class="bi bi-exclamation-circle" aria-hidden="true"></i> Tanggal akhir tidak boleh lebih awal dari tanggal awal.</p>
            <div class="form-actions">
              <button type="button" class="btn btn-reset" @click="resetFilter"><i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i> Reset</button>
              <button type="submit" class="btn btn-generate"><i class="bi bi-file-earmark-text" aria-hidden="true"></i> Tampilkan Pratinjau</button>
            </div>
          </form>
        </section>

        <section class="result-card" aria-labelledby="result-title">
          <div class="section-header">
            <div>
              <h2 id="result-title">Pratinjau Laporan</h2>
              <p>Hasil berdasarkan parameter yang dipilih.</p>
            </div>
            <span v-if="rows.length" class="ready-chip"><i class="bi bi-check-circle" aria-hidden="true"></i> Siap ditinjau</span>
          </div>

          <div v-if="!hasSearched" class="empty-state">
            <div class="empty-state__icon"><i class="bi bi-file-earmark-medical" aria-hidden="true"></i></div>
            <h3>Belum ada pratinjau laporan</h3>
            <p>Pilih parameter laporan kemudian klik tombol tampilkan pratinjau.</p>
          </div>

          <div v-else-if="rows.length" class="preview-content">
            <div class="preview-notice"><i class="bi bi-info-circle" aria-hidden="true"></i><span>Ini adalah ringkasan parameter laporan. Data laporan terperinci memerlukan sumber data dari backend.</span></div>
            <div class="table-responsive">
              <table class="table align-middle mb-0">
                <thead><tr><th scope="col">Jenis Laporan</th><th scope="col">Unit</th><th scope="col">Sub Unit</th><th scope="col">Periode</th><th scope="col">Status</th></tr></thead>
                <tbody><tr v-for="row in rows" :key="row.id"><td class="report-name">{{ row.jenisLaporan }}</td><td>{{ row.unit }}</td><td>{{ row.subUnit }}</td><td>{{ row.tanggal }}</td><td><span class="status-chip">{{ row.status }}</span></td></tr></tbody>
              </table>
            </div>
          </div>

          <div v-else class="empty-state">
            <div class="empty-state__icon muted"><i class="bi bi-search" aria-hidden="true"></i></div>
            <h3>Pratinjau belum dapat ditampilkan</h3>
            <p>Lengkapi jenis laporan dan pastikan rentang tanggal sudah benar.</p>
          </div>
        </section>
      </div>
    </main>
  </AppLayout>
</template>

<style scoped>
.laporan-page { min-height: 100%; background: #f5f8fa; }.page-header { display:flex; align-items:center; justify-content:space-between; gap:1.5rem; padding:clamp(1.5rem,3vw,2.5rem); margin-bottom:1.5rem; color:#fff; background:linear-gradient(125deg,#087e8b,#159cab); border-radius:1.25rem; box-shadow:0 1rem 2.5rem rgba(8,126,139,.16); }.back-link { display:inline-flex; align-items:center; gap:.45rem; margin-bottom:1rem; color:rgba(255,255,255,.85); font-size:.875rem; text-decoration:none; }.back-link:hover,.back-link:focus-visible { color:#fff; text-decoration:underline; }.eyebrow { display:inline-flex; align-items:center; gap:.45rem; color:rgba(255,255,255,.8); font-size:.75rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; }.page-header h1 { margin:.4rem 0 .45rem; font-size:clamp(1.7rem,3vw,2.35rem); font-weight:750; }.page-header p { margin:0; color:rgba(255,255,255,.86); }.date-card { display:flex; align-items:center; gap:.6rem; max-width:13rem; padding:.8rem 1rem; color:#eaffff; font-size:.85rem; font-weight:600; background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.22); border-radius:.8rem; }.date-card i { font-size:1.1rem; }
.filter-card,.result-card { overflow:hidden; background:#fff; border:1px solid #e5edef; border-radius:1rem; box-shadow:0 .4rem 1.4rem rgba(25,55,70,.045); }.section-header { display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; padding:1.35rem 1.5rem 1.1rem; border-bottom:1px solid #edf1f3; }.section-header h2 { margin:0 0 .3rem; color:#2d3d46; font-size:1.15rem; font-weight:700; }.section-header h2 i { margin-right:.4rem; color:#16838e; }.section-header p { margin:0; color:#71828c; font-size:.84rem; }.filter-count,.ready-chip { display:inline-flex; align-items:center; gap:.35rem; padding:.4rem .65rem; color:#087e8b; font-size:.78rem; font-weight:700; white-space:nowrap; background:#e8f7f8; border-radius:999px; }.filter-grid { display:grid; grid-template-columns:1fr 1fr; gap:1rem; padding:1.3rem 1.5rem .7rem; }.full-width { grid-column:1 / -1; }.form-label { margin-bottom:.4rem; color:#4d606a; font-size:.83rem; font-weight:700; }.required { color:#cf3e45; }.form-select,.form-control { min-height:2.6rem; border-color:#dbe5e8; border-radius:.6rem; }.form-select:focus,.form-control:focus { border-color:#3faeb8; box-shadow:0 0 0 .2rem rgba(18,150,163,.14); }.date-error { display:flex; align-items:center; gap:.4rem; margin:.25rem 1.5rem 0; color:#bd3c45; font-size:.82rem; }.form-actions { display:flex; justify-content:flex-end; gap:.6rem; padding:1.1rem 1.5rem 1.4rem; }.btn-reset,.btn-generate { display:inline-flex; align-items:center; justify-content:center; gap:.4rem; min-height:2.6rem; padding:.45rem .9rem; font-weight:600; border-radius:.6rem; }.btn-reset { color:#5d7079; background:#fff; border:1px solid #d7e1e5; }.btn-reset:hover { color:#334951; background:#f3f7f8; }.btn-generate { color:#fff; background:#087e8b; border-color:#087e8b; }.btn-generate:hover,.btn-generate:focus-visible { color:#fff; background:#066d78; border-color:#066d78; }.empty-state { display:grid; justify-items:center; padding:4rem 1.5rem; text-align:center; }.empty-state__icon { display:grid; width:3.5rem; height:3.5rem; margin-bottom:1rem; color:#16838e; font-size:1.3rem; background:#e6f6f7; border-radius:50%; place-items:center; }.empty-state__icon.muted { color:#71828c; background:#eff3f4; }.empty-state h3 { margin:0 0 .45rem; color:#33444d; font-size:1.1rem; font-weight:700; }.empty-state p { max-width:28rem; margin:0; color:#71828c; }.preview-notice { display:flex; align-items:flex-start; gap:.55rem; margin:1.25rem 1.5rem 0; padding:.8rem .9rem; color:#5a7079; font-size:.83rem; background:#f0f8f8; border:1px solid #d9eeee; border-radius:.65rem; }.preview-notice i { color:#087e8b; }.preview-content { padding-bottom:1.25rem; }.table { margin-top:1.25rem; }.table thead th { padding:.8rem 1rem; color:#536670; font-size:.72rem; font-weight:700; letter-spacing:.04em; text-transform:uppercase; white-space:nowrap; background:#f3f7f8; border-bottom:1px solid #e1eaed; }.table tbody td { padding:.9rem 1rem; color:#52646d; border-color:#edf1f3; }.report-name { color:#2f414a !important; font-weight:700; }.status-chip { display:inline-block; padding:.3rem .55rem; color:#227648; font-size:.75rem; font-weight:700; white-space:nowrap; background:#e5f6ea; border-radius:999px; }
@media (max-width:767.98px) { .page-header,.section-header { align-items:flex-start; flex-direction:column; }.date-card { max-width:none; }.filter-grid { grid-template-columns:1fr; }.full-width { grid-column:auto; }.form-actions { flex-direction:column-reverse; }.form-actions button { width:100%; } }
</style>