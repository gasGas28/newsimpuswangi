<script setup>
import AppLayout from '@/Components/Layouts/AppLayouts.vue'
defineOptions({ layout: AppLayout })

import { ref, computed } from 'vue'

const filter = ref({
  laporan: '',
  tgl_awal: '',
  tgl_akhir: '',
  unit: '',
  sub_unit: ''
})

const tampilkanTabel = ref(false)
const searchQuery = ref('')
const page = ref(1)
const itemsPerPage = 5

const dataDummy = ref([
  { nama: 'Ani', unit: 'IGD', tanggal: '2025-07-31' },
  { nama: 'Budi', unit: 'Rawat Inap', tanggal: '2025-07-30' },
  { nama: 'Cici', unit: 'IGD', tanggal: '2025-07-29' },
  { nama: 'Deni', unit: 'Rawat Inap', tanggal: '2025-07-28' },
  { nama: 'Eka', unit: 'IGD', tanggal: '2025-07-27' },
  { nama: 'Fani', unit: 'IGD', tanggal: '2025-07-26' },
  { nama: 'Gilang', unit: 'Rawat Inap', tanggal: '2025-07-25' }
])

const tampilkanData = () => {
  tampilkanTabel.value = true
  page.value = 1
}

const downloadExcel = () => {
  alert('Fitur download belum tersedia.')
}

const filteredData = computed(() => {
  if (!searchQuery.value) return dataDummy.value
  return dataDummy.value.filter(item =>
    item.nama.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
})

const paginatedData = computed(() => {
  const start = (page.value - 1) * itemsPerPage
  return filteredData.value.slice(start, start + itemsPerPage)
})

const totalPages = computed(() => {
  return Math.ceil(filteredData.value.length / itemsPerPage)
})

const showingStart = computed(() => {
  return filteredData.value.length === 0 ? 0 : (page.value - 1) * itemsPerPage + 1
})

const showingEnd = computed(() => {
  const end = page.value * itemsPerPage
  return end > filteredData.value.length ? filteredData.value.length : end
})
</script>

<template>
    <div class="card shadow-sm">
      <div class="card-header bg-light">
        <strong>Filter Data Laporan Farmasi</strong>
      </div>
      <div class="card-body">
        <form @submit.prevent="tampilkanData">
          <div class="row mb-3">
            <div class="col-md-2"><label>Laporan</label></div>
            <div class="col-md-4">
              <select class="form-select" v-model="filter.laporan">
                <option value="">Pilih laporan</option>
                <option value="register">Laporan register pasien</option>
              </select>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-2"><label>Tgl Awal</label></div>
            <div class="col-md-4">
              <input type="date" class="form-control" v-model="filter.tgl_awal" />
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-2"><label>Tgl Akhir</label></div>
            <div class="col-md-4">
              <input type="date" class="form-control" v-model="filter.tgl_akhir" />
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-2"><label>Unit</label></div>
            <div class="col-md-4">
              <select class="form-select" v-model="filter.unit">
                <option value="">- Pilih -</option>
                <option value="igd">IGD</option>
                <option value="rawat_inap">Rawat Inap</option>
              </select>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-2"><label>Sub Unit</label></div>
            <div class="col-md-4">
              <select class="form-select" v-model="filter.sub_unit">
                <option value="">- Pilih -</option>
                <option value="umum">Umum</option>
                <option value="bpjs">BPJS</option>
              </select>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 offset-md-2">
              <button class="btn btn-primary me-2">
                <i class="bi bi-search"></i> Tampilkan Data
              </button>
              <button class="btn btn-info text-white" @click.prevent="downloadExcel">
                <i class="bi bi-download"></i> Download Excel
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div v-if="tampilkanTabel" class="card mt-4">
      <div class="card-body">
        <h5>Hasil Laporan</h5>
        <div class="d-flex justify-content-end mb-2">
          <input type="text" class="form-control w-25" placeholder="Search..." v-model="searchQuery" />
        </div>
        <table class="table table-bordered table-sm">
          <thead class="table-light">
            <tr>
              <th>No</th>
              <th>Nama Pasien</th>
              <th>Unit</th>
              <th>Tanggal</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(row, index) in paginatedData" :key="index">
              <td>{{ (page - 1) * itemsPerPage + index + 1 }}</td>
              <td>{{ row.nama }}</td>
              <td>{{ row.unit }}</td>
              <td>{{ row.tanggal }}</td>
            </tr>
            <tr v-if="filteredData.length === 0">
              <td colspan="4" class="text-center">Tidak ada data</td>
            </tr>
          </tbody>
        </table>

        <div class="d-flex justify-content-between align-items-center">
          <small>
            Showing {{ showingStart }} to {{ showingEnd }} of {{ filteredData.length }} entries
          </small>
          <div>
            <button class="btn btn-outline-secondary btn-sm me-1" :disabled="page === 1" @click="page--">Previous</button>
            <button class="btn btn-outline-secondary btn-sm" :disabled="page >= totalPages" @click="page++">Next</button>
          </div>
        </div>
      </div>
    </div>
</template>
