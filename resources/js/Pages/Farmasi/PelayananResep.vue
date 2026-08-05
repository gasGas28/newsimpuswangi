<script setup>
import { computed, reactive, watch } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Components/Layouts/AppLayouts.vue'

const props = defineProps({
  units: { type: Array, default: () => [] },
  subUnits: { type: Array, default: () => [] },
  data: { type: Array, default: () => [] },
  filters: { type: Object, default: () => ({}) },
})

const filters = reactive({
  unit: props.filters.unit ?? '',
  sub_unit: props.filters.sub_unit ?? '',
  periode: props.filters.periode ?? '',
})

const filteredSubUnits = computed(() => {
  if (!filters.unit) return props.subUnits
  return props.subUnits.filter((subUnit) => String(subUnit.id_kategori) === String(filters.unit))
})

const activeFilterCount = computed(() => Object.values(filters).filter(Boolean).length)

watch(() => filters.unit, () => {
  if (!filteredSubUnits.value.some((subUnit) => String(subUnit.id) === String(filters.sub_unit))) {
    filters.sub_unit = ''
  }
})

const applyFilter = () => {
  router.get('/farmasi/pelayanan-resep', filters, {
    preserveScroll: true,
    preserveState: true,
  })
}

const resetFilter = () => {
  filters.unit = ''
  filters.sub_unit = ''
  filters.periode = ''
  applyFilter()
}

const statusClass = (status) => {
  const value = String(status ?? '').toLowerCase()
  if (value.includes('selesai') || value.includes('sudah') || value.includes('terlayani')) return 'status-success'
  if (value.includes('batal') || value.includes('tolak')) return 'status-danger'
  return 'status-pending'
}
</script>

<template>
  <AppLayout title="Pelayanan Resep">
    <main class="pelayanan-resep-page">
      <div class="container py-4 py-lg-5">
        <section class="page-header" aria-labelledby="page-title">
          <div>
            <Link href="/farmasi" class="back-link"><i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali ke Farmasi</Link>
            <span class="eyebrow"><i class="bi bi-hospital" aria-hidden="true"></i> Pelayanan farmasi</span>
            <h1 id="page-title">Resep dari Poli</h1>
            <p>Monitor dan telusuri resep pasien yang masuk dari unit pelayanan.</p>
          </div>
          <div class="header-summary">
            <span class="header-summary__icon"><i class="bi bi-prescription2" aria-hidden="true"></i></span>
            <span><strong>{{ data.length }}</strong> resep ditampilkan</span>
          </div>
        </section>

        <section class="filter-card mb-4" aria-labelledby="filter-title">
          <div class="filter-card__header">
            <div>
              <h2 id="filter-title"><i class="bi bi-funnel" aria-hidden="true"></i> Filter Resep</h2>
              <p>Saring data berdasarkan asal unit dan tanggal pelayanan.</p>
            </div>
            <span v-if="activeFilterCount" class="active-filter">{{ activeFilterCount }} filter aktif</span>
          </div>

          <form class="filter-grid" @submit.prevent="applyFilter">
            <div>
              <label for="unit" class="form-label">Unit</label>
              <select id="unit" v-model="filters.unit" class="form-select">
                <option value="">Semua unit</option>
                <option v-for="unit in units" :key="unit.id" :value="unit.id">{{ unit.nama }}</option>
              </select>
            </div>
            <div>
              <label for="sub-unit" class="form-label">Sub Unit</label>
              <select id="sub-unit" v-model="filters.sub_unit" class="form-select" :disabled="!filteredSubUnits.length">
                <option value="">Semua sub unit</option>
                <option v-for="subUnit in filteredSubUnits" :key="subUnit.id" :value="subUnit.id">{{ subUnit.nama }}</option>
              </select>
            </div>
            <div>
              <label for="periode" class="form-label">Tanggal Pelayanan</label>
              <input id="periode" v-model="filters.periode" type="date" class="form-control">
            </div>
            <div class="filter-actions">
              <button type="button" class="btn btn-reset" :disabled="!activeFilterCount" @click="resetFilter">
                <i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i>
                Reset
              </button>
              <button type="submit" class="btn btn-filter">
                <i class="bi bi-search" aria-hidden="true"></i>
                Tampilkan
              </button>
            </div>
          </form>
        </section>

        <section class="table-card" aria-labelledby="list-title">
          <div class="table-card__header">
            <div>
              <h2 id="list-title">Daftar Resep Pasien</h2>
              <p>{{ data.length ? 'Data resep yang sesuai dengan filter saat ini.' : 'Belum ada resep yang sesuai dengan filter.' }}</p>
            </div>
            <span class="result-chip"><i class="bi bi-clipboard2-pulse" aria-hidden="true"></i> {{ data.length }} data</span>
          </div>

          <div v-if="data.length" class="table-responsive resep-table">
            <table class="table align-middle mb-0">
              <thead>
                <tr>
                  <th scope="col">Pasien</th>
                  <th scope="col">Poli & Diagnosa</th>
                  <th scope="col">Obat & Dosis</th>
                  <th scope="col" class="text-center">Jumlah</th>
                  <th scope="col" class="text-center">Stok Unit</th>
                  <th scope="col">Status</th>
                  <th scope="col">Tanggal</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="row in data" :key="row.id">
                  <td>
                    <div class="patient-name">{{ row.pasien || '-' }}</div>
                    <div class="patient-rm">RM: {{ row.no_rm || '-' }}</div>
                    <div v-if="row.alamat" class="patient-address">{{ row.alamat }}</div>
                  </td>
                  <td>
                    <div class="main-cell">{{ row.poli || '-' }}</div>
                    <div class="sub-cell">{{ row.diagnosa || 'Tanpa diagnosa' }}</div>
                  </td>
                  <td>
                    <div class="main-cell">{{ row.nama_obat || '-' }}</div>
                    <div class="sub-cell">{{ row.dosis || 'Dosis belum dicantumkan' }}</div>
                  </td>
                  <td class="text-center"><span class="quantity-chip">{{ row.jumlah ?? '-' }}</span></td>
                  <td class="text-center"><span class="stock-chip">{{ row.stok_unit ?? '-' }}</span></td>
                  <td><span class="status-badge" :class="statusClass(row.status_resep)">{{ row.status_resep || 'Menunggu' }}</span></td>
                  <td class="date-cell">{{ row.created_at || '-' }}</td>
                </tr>
              </tbody>
            </table>
          </div>

          <div v-else class="empty-state">
            <div class="empty-state__icon"><i class="bi bi-clipboard-x" aria-hidden="true"></i></div>
            <h3>Tidak ada resep ditemukan</h3>
            <p>Ubah atau hapus filter untuk melihat resep dari unit pelayanan lain.</p>
            <button v-if="activeFilterCount" type="button" class="btn btn-reset" @click="resetFilter"><i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i> Reset Filter</button>
          </div>
        </section>
      </div>
    </main>
  </AppLayout>
</template>

<style scoped>
.pelayanan-resep-page { min-height: 100%; background: #f5f8fa; }
.page-header { display: flex; align-items: center; justify-content: space-between; gap: 1.5rem; padding: clamp(1.5rem, 3vw, 2.5rem); margin-bottom: 1.5rem; color: #fff; background: linear-gradient(125deg, #087e8b, #159cab); border-radius: 1.25rem; box-shadow: 0 1rem 2.5rem rgba(8,126,139,.16); }
.back-link { display: inline-flex; align-items: center; gap: .45rem; margin-bottom: 1rem; color: rgba(255,255,255,.85); font-size: .875rem; text-decoration: none; }
.back-link:hover, .back-link:focus-visible { color: #fff; text-decoration: underline; }
.eyebrow { display: inline-flex; align-items: center; gap: .45rem; color: rgba(255,255,255,.8); font-size: .75rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }
.page-header h1 { margin: .4rem 0 .45rem; font-size: clamp(1.7rem,3vw,2.35rem); font-weight: 750; }
.page-header p { margin: 0; color: rgba(255,255,255,.86); }
.header-summary { display: flex; align-items: center; gap: .75rem; flex: 0 0 auto; padding: .8rem 1rem; color: #eaffff; font-size: .875rem; background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.22); border-radius: .85rem; }
.header-summary strong { display: block; color: #fff; font-size: 1.35rem; line-height: 1; }
.header-summary__icon { display: grid; width: 2.5rem; height: 2.5rem; font-size: 1.1rem; background: rgba(255,255,255,.14); border-radius: .65rem; place-items: center; }
.filter-card, .table-card { overflow: hidden; background: #fff; border: 1px solid #e5edef; border-radius: 1rem; box-shadow: 0 .4rem 1.4rem rgba(25,55,70,.045); }
.filter-card__header, .table-card__header { display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; padding: 1.35rem 1.5rem 1.1rem; border-bottom: 1px solid #edf1f3; }
.filter-card h2, .table-card h2 { margin: 0 0 .3rem; color: #2d3d46; font-size: 1.15rem; font-weight: 700; }
.filter-card h2 i { margin-right: .4rem; color: #16838e; }
.filter-card p, .table-card p { margin: 0; color: #71828c; font-size: .84rem; }
.active-filter, .result-chip { display: inline-flex; align-items: center; gap: .35rem; padding: .4rem .65rem; color: #087e8b; font-size: .78rem; font-weight: 700; white-space: nowrap; background: #e8f7f8; border-radius: 999px; }
.filter-grid { display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 1rem; align-items: end; padding: 1.25rem 1.5rem; }
.form-label { margin-bottom: .4rem; color: #4d606a; font-size: .83rem; font-weight: 700; }
.form-select, .form-control { min-height: 2.6rem; border-color: #dbe5e8; border-radius: .6rem; }
.form-select:focus, .form-control:focus { border-color: #3faeb8; box-shadow: 0 0 0 .2rem rgba(18,150,163,.14); }
.filter-actions { display: flex; gap: .55rem; }
.btn-reset, .btn-filter { display: inline-flex; align-items: center; justify-content: center; gap: .4rem; min-height: 2.6rem; padding: .45rem .85rem; font-weight: 600; border-radius: .6rem; }
.btn-reset { color: #5d7079; background: #fff; border: 1px solid #d7e1e5; }
.btn-reset:hover { color: #334951; background: #f3f7f8; }
.btn-filter { color: #fff; background: #087e8b; border-color: #087e8b; }
.btn-filter:hover, .btn-filter:focus-visible { color: #fff; background: #066d78; border-color: #066d78; }
.resep-table { max-height: 34rem; }
.table thead th { position: sticky; top: 0; z-index: 1; padding: .85rem 1rem; color: #536670; font-size: .72rem; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; white-space: nowrap; background: #f3f7f8; border-bottom: 1px solid #e1eaed; }
.table tbody td { padding: .9rem 1rem; color: #52646d; vertical-align: middle; border-color: #edf1f3; }
.table tbody tr:hover { background: #f7fcfc; }
.patient-name, .main-cell { color: #2e3f49; font-weight: 700; }
.patient-rm, .sub-cell, .patient-address { margin-top: .2rem; color: #74868f; font-size: .78rem; }
.patient-address { max-width: 12rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.quantity-chip, .stock-chip { display: inline-block; min-width: 2.1rem; padding: .25rem .48rem; font-size: .8rem; font-weight: 700; border-radius: .35rem; }
.quantity-chip { color: #39717a; background: #eaf7f8; }
.stock-chip { color: #61727b; background: #f0f3f5; }
.status-badge { display: inline-block; padding: .32rem .55rem; font-size: .75rem; font-weight: 700; white-space: nowrap; border-radius: 999px; }
.status-success { color: #227648; background: #e5f6ea; }.status-danger { color: #a33b43; background: #fbe9ea; }.status-pending { color: #9a6a17; background: #fff4dd; }
.date-cell { color: #6c7e87; font-size: .82rem; white-space: nowrap; }
.empty-state { display: grid; justify-items: center; padding: 4rem 1.5rem; text-align: center; }
.empty-state__icon { display: grid; width: 3.5rem; height: 3.5rem; margin-bottom: 1rem; color: #16838e; font-size: 1.3rem; background: #e6f6f7; border-radius: 50%; place-items: center; }
.empty-state h3 { margin: 0 0 .45rem; color: #33444d; font-size: 1.1rem; font-weight: 700; }.empty-state p { max-width: 25rem; margin-bottom: 1.1rem; }
@media (max-width: 991.98px) { .filter-grid { grid-template-columns: 1fr 1fr; }.filter-actions { justify-content: flex-end; } }
@media (max-width: 767.98px) { .page-header, .filter-card__header, .table-card__header { align-items: flex-start; flex-direction: column; }.header-summary { align-self: stretch; }.filter-grid { grid-template-columns: 1fr; }.filter-actions { justify-content: stretch; }.filter-actions button { flex: 1; } }
</style>