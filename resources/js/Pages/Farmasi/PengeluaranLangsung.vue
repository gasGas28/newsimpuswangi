<script setup>
import { computed, reactive, ref, watch } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import axios from 'axios'
import AppLayout from '@/Components/Layouts/AppLayouts.vue'

const props = defineProps({
  units: { type: Array, default: () => [] },
  subUnits: { type: Array, default: () => [] },
  keperluan: { type: Array, default: () => [] },
  dataTable: { type: Array, default: () => [] },
  filters: { type: Object, default: () => ({}) },
})

const filters = reactive({
  unit: props.filters.unit ?? '',
  subunit: props.filters.subunit ?? '',
  periode: props.filters.periode ?? '',
  keperluan: props.filters.keperluan ?? '',
})

const subUnits = ref(props.subUnits)
const loadingSubUnits = ref(false)

const activeFilterCount = computed(() => Object.values(filters).filter(Boolean).length)
const totalItems = computed(() => props.dataTable.reduce((total, row) => total + Number(row.jumlah_obat ?? 0), 0))

watch(() => filters.unit, async (unit, previousUnit) => {
  if (unit === previousUnit) return

  filters.subunit = ''

  if (!unit) {
    subUnits.value = []
    return
  }

  loadingSubUnits.value = true
  try {
    const response = await axios.get('/farmasi/get-sub-units', { params: { unit } })
    subUnits.value = response.data
  } catch (error) {
    subUnits.value = []
    console.error('Gagal memuat sub unit:', error)
  } finally {
    loadingSubUnits.value = false
  }
})

const applyFilter = () => {
  router.get('/farmasi/pengeluaran-langsung', filters, {
    preserveScroll: true,
    preserveState: true,
  })
}

const resetFilter = () => {
  filters.unit = ''
  filters.subunit = ''
  filters.periode = ''
  filters.keperluan = ''
  subUnits.value = []
  applyFilter()
}
</script>

<template>
  <AppLayout title="Pengeluaran Langsung">
    <main class="pengeluaran-page">
      <div class="container py-4 py-lg-5">
        <section class="page-header" aria-labelledby="page-title">
          <div>
            <Link href="/farmasi" class="back-link"><i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali ke Farmasi</Link>
            <span class="eyebrow"><i class="bi bi-box-arrow-up-right" aria-hidden="true"></i> Distribusi obat</span>
            <h1 id="page-title">Pengeluaran Langsung</h1>
            <p>Tinjau riwayat pengeluaran obat untuk unit dan kebutuhan pelayanan.</p>
          </div>
          <div class="header-metrics">
            <div><strong>{{ dataTable.length }}</strong><span>catatan</span></div>
            <div><strong>{{ totalItems }}</strong><span>item obat</span></div>
          </div>
        </section>

        <section class="filter-card mb-4" aria-labelledby="filter-title">
          <div class="section-header">
            <div>
              <h2 id="filter-title"><i class="bi bi-funnel" aria-hidden="true"></i> Filter Pengeluaran</h2>
              <p>Saring riwayat berdasarkan unit, sub unit, bulan, atau keperluan.</p>
            </div>
            <span v-if="activeFilterCount" class="filter-count">{{ activeFilterCount }} filter aktif</span>
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
              <label for="subunit" class="form-label">Sub Unit</label>
              <select id="subunit" v-model="filters.subunit" class="form-select" :disabled="!filters.unit || loadingSubUnits">
                <option value="">{{ loadingSubUnits ? 'Memuat sub unit...' : 'Semua sub unit' }}</option>
                <option v-for="subUnit in subUnits" :key="subUnit.id_detail" :value="subUnit.id_detail">{{ subUnit.nama_unit }}</option>
              </select>
            </div>
            <div>
              <label for="periode" class="form-label">Bulan Pengeluaran</label>
              <input id="periode" v-model="filters.periode" type="month" class="form-control">
            </div>
            <div>
              <label for="keperluan" class="form-label">Keperluan</label>
              <select id="keperluan" v-model="filters.keperluan" class="form-select">
                <option value="">Semua keperluan</option>
                <option v-for="item in keperluan" :key="item.id" :value="item.nama">{{ item.nama }}</option>
              </select>
            </div>
            <div class="filter-actions">
              <button type="button" class="btn btn-reset" :disabled="!activeFilterCount" @click="resetFilter"><i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i> Reset</button>
              <button type="submit" class="btn btn-filter"><i class="bi bi-search" aria-hidden="true"></i> Tampilkan</button>
            </div>
          </form>
        </section>

        <section class="table-card" aria-labelledby="table-title">
          <div class="section-header">
            <div>
              <h2 id="table-title">Riwayat Pengeluaran</h2>
              <p>{{ dataTable.length ? 'Ringkasan pengeluaran obat sesuai filter yang dipilih.' : 'Belum ada pengeluaran yang sesuai dengan filter.' }}</p>
            </div>
            <span class="result-chip"><i class="bi bi-capsule" aria-hidden="true"></i> {{ totalItems }} item obat</span>
          </div>

          <div v-if="dataTable.length" class="table-responsive table-wrap">
            <table class="table align-middle mb-0">
              <thead>
                <tr>
                  <th scope="col">No.</th>
                  <th scope="col">Tanggal</th>
                  <th scope="col">Unit</th>
                  <th scope="col">Keperluan</th>
                  <th scope="col" class="text-center">Jumlah Obat</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(row, index) in dataTable" :key="`${row.tanggal}-${row.unit}-${row.keperluan}-${index}`">
                  <td class="number-cell">{{ index + 1 }}</td>
                  <td><span class="date-chip"><i class="bi bi-calendar3" aria-hidden="true"></i> {{ row.tanggal || '-' }}</span></td>
                  <td class="unit-cell">{{ row.unit || '-' }}</td>
                  <td><span class="purpose-chip">{{ row.keperluan || '-' }}</span></td>
                  <td class="text-center"><span class="quantity-chip">{{ row.jumlah_obat ?? 0 }}</span></td>
                </tr>
              </tbody>
            </table>
          </div>

          <div v-else class="empty-state">
            <div class="empty-state__icon"><i class="bi bi-inbox" aria-hidden="true"></i></div>
            <h3>Tidak ada pengeluaran ditemukan</h3>
            <p>Sesuaikan filter atau pilih periode lain untuk melihat riwayat pengeluaran obat.</p>
            <button v-if="activeFilterCount" type="button" class="btn btn-reset" @click="resetFilter"><i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i> Reset Filter</button>
          </div>
        </section>
      </div>
    </main>
  </AppLayout>
</template>

<style scoped>
.pengeluaran-page { min-height: 100%; background: #f5f8fa; }
.page-header { display: flex; align-items: center; justify-content: space-between; gap: 1.5rem; padding: clamp(1.5rem,3vw,2.5rem); margin-bottom: 1.5rem; color: #fff; background: linear-gradient(125deg,#087e8b,#159cab); border-radius: 1.25rem; box-shadow: 0 1rem 2.5rem rgba(8,126,139,.16); }
.back-link { display: inline-flex; align-items: center; gap: .45rem; margin-bottom: 1rem; color: rgba(255,255,255,.85); font-size: .875rem; text-decoration: none; }.back-link:hover,.back-link:focus-visible { color: #fff; text-decoration: underline; }
.eyebrow { display: inline-flex; align-items: center; gap: .45rem; color: rgba(255,255,255,.8); font-size: .75rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }.page-header h1 { margin: .4rem 0 .45rem; font-size: clamp(1.7rem,3vw,2.35rem); font-weight: 750; }.page-header p { margin: 0; color: rgba(255,255,255,.86); }
.header-metrics { display: flex; gap: .7rem; flex: 0 0 auto; }.header-metrics div { min-width: 5rem; padding: .7rem .85rem; text-align: center; background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.22); border-radius: .75rem; }.header-metrics strong { display: block; font-size: 1.3rem; line-height: 1.1; }.header-metrics span { color: rgba(255,255,255,.82); font-size: .72rem; }
.filter-card,.table-card { overflow: hidden; background: #fff; border: 1px solid #e5edef; border-radius: 1rem; box-shadow: 0 .4rem 1.4rem rgba(25,55,70,.045); }.section-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; padding: 1.35rem 1.5rem 1.1rem; border-bottom: 1px solid #edf1f3; }.section-header h2 { margin: 0 0 .3rem; color: #2d3d46; font-size: 1.15rem; font-weight: 700; }.section-header h2 i { margin-right: .4rem; color: #16838e; }.section-header p { margin: 0; color: #71828c; font-size: .84rem; }
.filter-count,.result-chip { display: inline-flex; align-items: center; gap: .35rem; padding: .4rem .65rem; color: #087e8b; font-size: .78rem; font-weight: 700; white-space: nowrap; background: #e8f7f8; border-radius: 999px; }.filter-grid { display: grid; grid-template-columns: 1fr 1fr 1fr 1fr auto; gap: 1rem; align-items: end; padding: 1.25rem 1.5rem; }.form-label { margin-bottom: .4rem; color: #4d606a; font-size: .83rem; font-weight: 700; }.form-select,.form-control { min-height: 2.6rem; border-color: #dbe5e8; border-radius: .6rem; }.form-select:focus,.form-control:focus { border-color: #3faeb8; box-shadow: 0 0 0 .2rem rgba(18,150,163,.14); }.filter-actions { display: flex; gap: .55rem; }.btn-reset,.btn-filter { display: inline-flex; align-items: center; justify-content: center; gap: .4rem; min-height: 2.6rem; padding: .45rem .85rem; font-weight: 600; border-radius: .6rem; }.btn-reset { color: #5d7079; background: #fff; border: 1px solid #d7e1e5; }.btn-reset:hover { color: #334951; background: #f3f7f8; }.btn-filter { color: #fff; background: #087e8b; border-color: #087e8b; }.btn-filter:hover,.btn-filter:focus-visible { color: #fff; background: #066d78; border-color: #066d78; }
.table-wrap { max-height: 34rem; }.table thead th { position: sticky; top: 0; z-index: 1; padding: .85rem 1rem; color: #536670; font-size: .72rem; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; white-space: nowrap; background: #f3f7f8; border-bottom: 1px solid #e1eaed; }.table tbody td { padding: .9rem 1rem; color: #52646d; border-color: #edf1f3; }.table tbody tr:hover { background: #f7fcfc; }.number-cell { color: #80919a; font-size: .83rem; font-weight: 700; }.unit-cell { color: #2f414a !important; font-weight: 700; }.date-chip,.purpose-chip,.quantity-chip { display: inline-flex; align-items: center; gap: .35rem; padding: .28rem .52rem; font-size: .8rem; font-weight: 600; border-radius: .4rem; }.date-chip { color: #526e78; background: #f0f5f6; }.purpose-chip { color: #39717a; background: #eaf7f8; }.quantity-chip { min-width: 2.2rem; justify-content: center; color: #087e8b; background: #e3f6f7; }
.empty-state { display: grid; justify-items: center; padding: 4rem 1.5rem; text-align: center; }.empty-state__icon { display: grid; width: 3.5rem; height: 3.5rem; margin-bottom: 1rem; color: #16838e; font-size: 1.3rem; background: #e6f6f7; border-radius: 50%; place-items: center; }.empty-state h3 { margin: 0 0 .45rem; color: #33444d; font-size: 1.1rem; font-weight: 700; }.empty-state p { max-width: 25rem; margin-bottom: 1.1rem; }
@media (max-width: 1199.98px) { .filter-grid { grid-template-columns: 1fr 1fr; }.filter-actions { justify-content: flex-end; } }@media (max-width: 767.98px) { .page-header,.section-header { align-items: flex-start; flex-direction: column; }.header-metrics { width: 100%; }.header-metrics div { flex: 1; }.filter-grid { grid-template-columns: 1fr; }.filter-actions { justify-content: stretch; }.filter-actions button { flex: 1; } }
</style>