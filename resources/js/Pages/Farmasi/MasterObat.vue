<script setup>
import { computed, ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Components/Layouts/AppLayouts.vue'

const props = defineProps({
  obatList: {
    type: Array,
    default: () => [],
  },
  today: {
    type: String,
    default: '',
  },
})

const search = ref('')

const filteredObat = computed(() => {
  const keyword = search.value.trim().toLowerCase()

  if (!keyword) return props.obatList

  return props.obatList.filter((obat) =>
    [obat.NAMA, obat.KODE_OBAT, obat.SATUAN]
      .filter(Boolean)
      .some((value) => String(value).toLowerCase().includes(keyword)),
  )
})

const resultLabel = computed(() => {
  const total = filteredObat.value.length
  return `${total} ${total === 1 ? 'obat' : 'obat'} ditemukan`
})

const clearSearch = () => {
  search.value = ''
}

const goTambah = () => {
  router.visit('/farmasi/master-obat/tambah')
}

const goBack = () => {
  router.visit('/farmasi')
}
</script>

<template>
  <AppLayout title="Master Obat">
    <main class="master-obat-page">
      <div class="container py-4 py-lg-5">
        <section class="page-header" aria-labelledby="page-title">
          <div class="page-header__intro">
            <Link href="/farmasi" class="back-link">
              <i class="bi bi-arrow-left" aria-hidden="true"></i>
              Kembali ke Farmasi
            </Link>
            <span class="eyebrow"><i class="bi bi-box-seam" aria-hidden="true"></i> Data persediaan</span>
            <h1 id="page-title">Master Obat</h1>
            <p>Kelola daftar obat yang tersedia untuk mendukung pelayanan farmasi.</p>
          </div>

          <div class="page-header__actions">
            <span v-if="today" class="date-chip">
              <i class="bi bi-calendar3" aria-hidden="true"></i>
              {{ today }}
            </span>
            <button type="button" class="btn btn-light btn-back" @click="goBack">
              <i class="bi bi-grid" aria-hidden="true"></i>
              Menu Farmasi
            </button>
            <button type="button" class="btn btn-add" @click="goTambah">
              <i class="bi bi-plus-lg" aria-hidden="true"></i>
              Tambah Obat
            </button>
          </div>
        </section>

        <section class="content-card" aria-labelledby="list-title">
          <div class="content-card__header">
            <div>
              <h2 id="list-title">Daftar Obat</h2>
              <p>Gunakan pencarian untuk menemukan obat berdasarkan kode, nama, atau satuan.</p>
            </div>
            <span class="total-chip"><i class="bi bi-capsule" aria-hidden="true"></i> {{ obatList.length }} total obat</span>
          </div>

          <div class="toolbar">
            <div class="search-field">
              <i class="bi bi-search" aria-hidden="true"></i>
              <input
                v-model="search"
                type="search"
                class="form-control"
                placeholder="Cari kode, nama, atau satuan obat..."
                aria-label="Cari obat"
              >
              <button
                v-if="search"
                type="button"
                class="clear-search"
                aria-label="Hapus pencarian"
                @click="clearSearch"
              >
                <i class="bi bi-x-lg" aria-hidden="true"></i>
              </button>
            </div>
            <span class="result-label">{{ resultLabel }}</span>
          </div>

          <div v-if="filteredObat.length" class="table-responsive table-wrap">
            <table class="table align-middle mb-0">
              <thead>
                <tr>
                  <th scope="col">Kode Obat</th>
                  <th scope="col">Nama Obat</th>
                  <th scope="col">Satuan</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="obat in filteredObat" :key="obat.OBAT_ID">
                  <td><span class="medicine-code">{{ obat.KODE_OBAT }}</span></td>
                  <td class="medicine-name">{{ obat.NAMA }}</td>
                  <td><span class="unit-chip">{{ obat.SATUAN || '-' }}</span></td>
                </tr>
              </tbody>
            </table>
          </div>

          <div v-else class="empty-state">
            <div class="empty-state__icon"><i class="bi bi-search" aria-hidden="true"></i></div>
            <h3>{{ search ? 'Obat tidak ditemukan' : 'Belum ada data obat' }}</h3>
            <p>{{ search ? 'Coba gunakan kata kunci lain atau hapus pencarian.' : 'Tambahkan obat pertama untuk mulai mengelola data persediaan.' }}</p>
            <button v-if="search" type="button" class="btn btn-outline-secondary" @click="clearSearch">Hapus pencarian</button>
            <button v-else type="button" class="btn btn-add" @click="goTambah"><i class="bi bi-plus-lg" aria-hidden="true"></i> Tambah Obat</button>
          </div>
        </section>
      </div>
    </main>
  </AppLayout>
</template>

<style scoped>
.master-obat-page { min-height: 100%; background: #f5f8fa; }
.page-header { display: flex; justify-content: space-between; gap: 2rem; padding: clamp(1.5rem, 3vw, 2.5rem); margin-bottom: 1.5rem; color: #fff; background: linear-gradient(125deg, #087e8b, #159cab); border-radius: 1.25rem; box-shadow: 0 1rem 2.5rem rgba(8, 126, 139, 0.16); }
.page-header__intro { max-width: 42rem; }
.back-link { display: inline-flex; align-items: center; gap: .45rem; margin-bottom: 1rem; color: rgba(255,255,255,.85); font-size: .875rem; text-decoration: none; }
.back-link:hover, .back-link:focus-visible { color: #fff; text-decoration: underline; }
.eyebrow { display: inline-flex; align-items: center; gap: .45rem; color: rgba(255,255,255,.8); font-size: .75rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }
.page-header h1 { margin: .4rem 0 .5rem; font-size: clamp(1.7rem, 3vw, 2.35rem); font-weight: 750; }
.page-header p { margin: 0; color: rgba(255,255,255,.86); }
.page-header__actions { display: flex; flex: 0 0 auto; align-self: center; flex-wrap: wrap; justify-content: flex-end; gap: .65rem; max-width: 23rem; }
.date-chip, .total-chip, .result-label { display: inline-flex; align-items: center; gap: .4rem; font-size: .8rem; font-weight: 600; }
.date-chip { width: 100%; justify-content: center; padding: .6rem .75rem; background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.24); border-radius: .7rem; }
.btn-back, .btn-add { display: inline-flex; align-items: center; gap: .45rem; border-radius: .65rem; font-weight: 600; }
.btn-add { color: #fff; background: #087e8b; border-color: #087e8b; }
.btn-add:hover, .btn-add:focus-visible { color: #fff; background: #066c77; border-color: #066c77; }
.page-header .btn-add { color: #087e8b; background: #fff; border-color: #fff; }
.page-header .btn-add:hover, .page-header .btn-add:focus-visible { color: #066c77; background: #eafafb; border-color: #eafafb; }
.content-card { overflow: hidden; background: #fff; border: 1px solid #e6edef; border-radius: 1rem; box-shadow: 0 .4rem 1.4rem rgba(25, 55, 70, .045); }
.content-card__header { display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; padding: 1.4rem 1.5rem 1.2rem; border-bottom: 1px solid #edf1f3; }
.content-card h2 { margin: 0 0 .35rem; color: #273640; font-size: 1.2rem; font-weight: 700; }
.content-card p { margin: 0; color: #6a7c87; font-size: .875rem; }
.total-chip { padding: .45rem .7rem; color: #087e8b; white-space: nowrap; background: #e7f7f8; border-radius: 999px; }
.toolbar { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 1rem 1.5rem; background: #fbfcfd; border-bottom: 1px solid #edf1f3; }
.search-field { position: relative; width: min(100%, 31rem); }
.search-field > i { position: absolute; top: 50%; left: .85rem; z-index: 1; color: #80929c; transform: translateY(-50%); }
.search-field .form-control { min-height: 2.65rem; padding-left: 2.45rem; padding-right: 2.45rem; border-color: #dce5e9; border-radius: .65rem; }
.search-field .form-control:focus { border-color: #42adb7; box-shadow: 0 0 0 .2rem rgba(18,150,163,.14); }
.clear-search { position: absolute; top: 50%; right: .35rem; width: 2rem; height: 2rem; color: #72858e; background: transparent; border: 0; border-radius: 50%; transform: translateY(-50%); }
.clear-search:hover { color: #33444d; background: #edf2f4; }
.result-label { color: #697b85; white-space: nowrap; }
.table-wrap { max-height: 32rem; }
.table thead th { position: sticky; top: 0; z-index: 1; padding: .85rem 1.5rem; color: #52656f; font-size: .75rem; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; background: #f3f7f8; border-bottom: 1px solid #e2eaed; }
.table tbody td { padding: .9rem 1.5rem; color: #50616b; border-color: #edf1f3; }
.table tbody tr { transition: background-color .15s ease; }
.table tbody tr:hover { background: #f7fcfc; }
.medicine-code { display: inline-block; padding: .25rem .48rem; color: #39717a; font-family: ui-monospace, SFMono-Regular, Menlo, monospace; font-size: .8rem; font-weight: 700; background: #edf7f8; border-radius: .35rem; }
.medicine-name { color: #2e3e47 !important; font-weight: 600; }
.unit-chip { padding: .25rem .55rem; color: #687983; font-size: .8rem; background: #f0f3f5; border-radius: 999px; }
.empty-state { display: grid; justify-items: center; padding: 4rem 1.5rem; text-align: center; }
.empty-state__icon { display: grid; width: 3.5rem; height: 3.5rem; margin-bottom: 1rem; color: #16838e; font-size: 1.3rem; background: #e6f6f7; border-radius: 50%; place-items: center; }
.empty-state h3 { margin: 0 0 .45rem; color: #33444d; font-size: 1.1rem; font-weight: 700; }
.empty-state p { max-width: 25rem; margin-bottom: 1.1rem; }
@media (max-width: 767.98px) { .page-header, .content-card__header, .toolbar { align-items: stretch; flex-direction: column; } .page-header__actions { align-self: auto; justify-content: flex-start; max-width: none; } .date-chip { width: auto; } .result-label { white-space: normal; } .table thead th, .table tbody td { padding-right: 1rem; padding-left: 1rem; } }
@media (prefers-reduced-motion: reduce) { .table tbody tr { transition: none; } }
</style>