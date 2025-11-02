<template>
  <AppLayouts>
    <div class="card m-4 rounded-4 rounded-bottom-0 shadow-sm">
      <!-- Header -->
      <div
        class="card-header d-flex justify-content-between align-items-center p-3 rounded-4 rounded-bottom-0"
        style="background: linear-gradient(135deg, #3b82f6, #10b981);"
      >
        <h1 class="m-0 fs-5 text-white">Pelayanan LaboratoriumQQ</h1>
        <Link :href="route('dashboard')" class="btn bg-white bg-opacity-25 border border-1 btn-sm text-white">
          <i class="fas fa-arrow-left me-1 text-white"></i> Kembali
        </Link>
      </div>

      <div class="card-body">
        <!-- Filter (non-aktif, hanya UI) -->
        <div class="shadow-sm rounded-5 border mb-4">
          <div
            class="card-header p-3 d-flex justify-content-between align-items-center rounded-top-5"
            style="background: linear-gradient(135deg, #e0f2fe, #dcfce7);"
          >
            <h2 class="m-0 fs-6 text-dark">Filter Data</h2>
          </div>

          <div class="card-body">
            <form @submit.prevent="TampilkanData = true">
<!-- Filter -->
<!-- Filter: Kategori -> Puskesmas -->
<div class="mb-3 row align-items-center">
  <label class="col-md-2 col-form-label fw-semibold">Unit</label>
  <div class="col-md-5">
    <div class="input-group">
      <!-- Kategori (mis. PUSKESMAS) -->
      <select class="form-control" v-model="selectedKategori">
        <option value="">Semua Kategori</option>
        <option v-for="k in kategoriOptions" :key="k.value" :value="k.value">
          {{ k.label }}
        </option>
      </select>

      <!-- Puskesmas (nama unit) -->
      <select class="form-control" v-model="selectedUnit" :disabled="unitOptions.length === 0">
        <option value="">Semua Puskesmas</option>
        <option v-for="u in unitOptions" :key="u.value" :value="u.value">
          {{ u.label }}
        </option>
      </select>
    </div>
  </div>
</div>


              <div class="mb-3 row align-items-center">
                <label class="col-md-2 col-form-label fw-semibold">Periode</label>
                <div class="col-md-5">
                  <input type="month" class="form-control" placeholder="YYYY-MM (contoh)" />
                </div>
              </div>

              <button class="btn btn-primary gradient-btn">
                <i class="bi bi-search me-2"></i>Tampilkan Data
              </button>
            </form>

            <!-- DEBUG -->
            <!-- <div class="small text-muted mt-3">
              <div>Debug (controller):</div>
              <pre class="bg-light p-2">{{ Debug }}</pre>
            </div> -->
          </div>
        </div>

        <!-- Tabel -->
        <div class="card mt-4 rounded-4 overflow-hidden" v-if="TampilkanData">
          <div class="card-header bg-white d-flex justify-content-end">
            <form class="w-100" style="max-width: 320px;">
              <input v-model="q" type="search" class="form-control" placeholder="Search nama / MR / alamat..." />
            </form>
          </div>
          <div class="card-body p-0">
            <table class="table table-bordered mb-0 align-middle">
              <thead class="table-light text-center">
                <tr>
                  <th style="width:56px;">NO</th>
                  <th>Nama Pasien</th>
                  <th>Alamat</th>
                  <th>Kategori</th>
                  <th>Nama Poli</th>
                  <th>Alasan di rujuk ke lab</th>
                  <th>Status layanan</th>
                  <th style="width:140px;">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in filteredRows" :key="`${item.idLoket}-${index}`" class="table-hover-row">
                  <td class="text-center">{{ index + 1 }}</td>
                  <td>
                    <div class="fw-semibold">{{ item.NAMA_LGKP || '(tanpa nama)' }}</div>
                    <div class="small text-muted">MR: {{ item.NO_MR }} • {{ item.tglKunjungan }}</div>
                  </td>
                  <td>{{ formatAlamat(item) }}</td>
                  <td>-</td>
                  <td>{{ item.nmPoli || '-' }}</td>
                  <td>{{ item.alasan || '-' }}</td>
                  <td class="text-center">
                    <span class="badge" :class="item.status === 'Selesai' ? 'bg-success' : 'bg-warning text-dark'">
                      {{ item.status }}
                    </span>
                  </td>
                  <td class="text-center">
                    <Link
                      class="btn btn-success"
                      :href="route('ruang-layanan.laborat.pemeriksaan', { loketId: item.idLoket })"
                    >
                      Detail
                    </Link>
                  </td>

                </tr>

                <tr v-if="filteredRows.length === 0">
                  <td colspan="8" class="text-center py-4 text-muted">
                    Tidak ada data untuk ditampilkan.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </div>
  </AppLayouts>
</template>

<script setup>
import { computed, ref } from 'vue'
import { usePage, Link } from '@inertiajs/vue3'
import AppLayouts from '../../../Components/Layouts/AppLayouts.vue'

const page = usePage()

const DataUnit = computed(() => page.props.DataUnit || [])
const Debug = computed(() => page.props.Debug || {})

const TampilkanData = ref(true)
const q = ref('')

// state filter
const selectedKec = ref('')       // value = no_kec
const selectedPkm = ref('')       // value = id_kategori (atau id unit detail, sesuaikan)
// props dari Inertia (pastikan controller kirim FilterKategori & FilterUnits)
const FilterKategori = computed(() => page.props.FilterKategori || [])
const FilterUnits    = computed(() => page.props.FilterUnits || [])

// state dropdown
const selectedKategori = ref('') // value: id_kategori dari data_master_unit
const selectedUnit     = ref('') // value: unit_id dari unit_profiles

// opsi dropdown-1: kategori
const kategoriOptions = computed(() =>
  FilterKategori.value.map(k => ({
    value: String(k.id_kategori),
    label: k.kategori || `(Kategori ${k.id_kategori})`
  }))
)

// opsi dropdown-2: puskesmas (nama unit) tergantung kategori terpilih
const unitOptions = computed(() => {
  const idKat = selectedKategori.value
  const base = idKat
    ? FilterUnits.value.filter(u => String(u.id_kategori) === String(idKat))
    : FilterUnits.value
  return base.map(u => ({
    value: String(u.unit_id),
    label: u.nama_unit || `(Unit ${u.unit_id})`
  }))
})

/* OPSIONAL: auto-select kategori PUSKESMAS kalau ada
onMounted(() => {
  if (!selectedKategori.value) {
    const pkm = FilterKategori.value.find(k => (k.kategori || '').toUpperCase() === 'PUSKESMAS')
    if (pkm) selectedKategori.value = String(pkm.id_kategori)
  }
})
*/

// list kecamatan unik
const kecOptions = computed(() => {
  const m = new Map()
  for (const u of DataUnit.value) {
    if (u.no_kec) m.set(u.no_kec, u.nama_kec || `KEC ${u.no_kec}`)
  }
  return Array.from(m, ([value, label]) => ({ value: String(value), label }))
})

// list puskesmas terfilter berdasar kecamatan terpilih
const pkmOptions = computed(() => {
  const noKec = selectedKec.value
  const list = noKec
    ? DataUnit.value.filter(u => String(u.no_kec) === String(noKec))
    : DataUnit.value
  // pakai id_kategori/nama_unit dari query controller
  return list.map(u => ({
    value: String(u.id_kategori),
    label: u.nama_unit || '(tanpa nama)'
  }))
})

// contoh filter tabel (opsional, kalau datanya mendukung kolom ini di DataPasien)
const DataPasien = computed(() => page.props.DataPasien || [])
const filteredRows = computed(() => {
  const s = q.value.toLowerCase().trim()
  let rows = DataPasien.value

  // kalau item punya no_kec/id_kategori di DataPasien, bisa difilter:
  if (selectedKec.value) {
    rows = rows.filter(r => String(r.no_kec || '') === String(selectedKec.value))
  }
  if (selectedPkm.value) {
    rows = rows.filter(r => String(r.id_kategori || '') === String(selectedPkm.value))
  }

  if (!s) return rows
  return rows.filter(it =>
    [it.NAMA_LGKP, it.NO_MR, it.alamat, it.nmPoli, it.alasan]
      .map(v => (v ? String(v).toLowerCase() : ''))
      .some(v => v.includes(s))
  )
})

function formatAlamat(it) {
  return [it.alamat, it.no_rt ? `RT ${it.no_rt}` : null, it.no_rw ? `RW ${it.no_rw}` : null]
    .filter(Boolean)
    .join(', ')
}
</script>


<style scoped>
.gradient-btn {
  background: linear-gradient(135deg, #3b82f6, #10b981) !important;
  border: none !important;
  transition: transform 0.15s ease, box-shadow 0.15s ease;
}
.gradient-btn:hover { transform: translateY(-1px); box-shadow: 0 .5rem 1rem rgba(16,185,129,.15); }
.table-hover-row:hover { background-color: #f8fafc; }
.card-header { border: none; }
.table th, .table td { vertical-align: middle; }
.table { --bs-table-bg: transparent; }


</style>
