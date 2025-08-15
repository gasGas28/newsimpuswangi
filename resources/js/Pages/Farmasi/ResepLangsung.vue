<template>
  <div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h2 class="fw-bold mb-0">Pengeluaran Langsung</h2>
        <p class="text-muted">Input resep langsung dari pasien ke farmasi</p>
      </div>
    </div>

    <!-- Form Filter -->
    <div class="card shadow-sm mb-4">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Filter Data</h5>
      </div>
      <div class="card-body">
        <form @submit.prevent="filterData">
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Unit</label>
              <select v-model="form.unit" class="form-select" required>
                <option value="">-- Pilih Unit --</option>
                <option>PUSKESMAS</option>
                <option>PUSTU</option>
                <option>POLINDES</option>
                <option>POSKESDES</option>
                <option>PUSLING</option>
                <option>POSKESTREN</option>
                <option>PONKESDES</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Sub Unit</label>
              <select v-model="form.subUnit" class="form-select">
                <option value="">-- Pilih Sub Unit --</option>
                <option>PUSKESMAS WONGSOREJO</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Periode</label>
              <input type="date" v-model="filter.periode" class="form-control" />
            </div>

            <div class="col-md-6">
              <label class="form-label">Keperluan</label>
              <select v-model="form.keperluan" class="form-select">
                <option value="">-- Pilih Keperluan --</option>
                <option>KLB</option>
                <option>BENCANA</option>
                <option>PPGD</option>
                <option>GIZI</option>
                <option>CITO</option>
                <option>UGD</option>
                <option>POSYANDU</option>
                <option>RANAP</option>
                <option>TBC</option>
              </select>
            </div>
          </div>

          <!-- Tombol -->
          <div class="d-flex justify-content-end gap-2">
            <button type="submit" class="btn btn-success">
              <i class="bi bi-arrow-repeat me-1"></i> Tampilkan Data
            </button>
            <button type="button" class="btn btn-info text-white">
              <i class="bi bi-save me-1"></i> Simpan Data
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Tabel Resep -->
    <vue-good-table
      :columns="columns"
      :rows="rows"
      :search-options="{ enabled: true }"
      :pagination-options="{ enabled: true, perPage: 10 }"
    />

    <!-- Tombol Kembali -->
    <div class="mt-4 text-start">
      <a href="/farmasi" class="btn btn-secondary">
        Kembali
      </a>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { VueGoodTable } from 'vue-good-table-next'
import 'vue-good-table-next/dist/vue-good-table-next.css'

const columns = [
  { label: 'No', field: 'nomorPasien' },
  { label: 'Tanggal', field: 'tanggal' },
  { label: 'Unit', field: 'unit' },
  { label: 'Keperluan', field: 'keperluan' },
  { label: 'Jumlah Obat', field: 'jumlahObat' },
  { label: 'Action', field: 'aksi' }
]

const rows = ref([]) // ✅ Perbaikan dari :rows=[]

const form = ref({
  unit: '',
  subUnit: '',
  keperluan: ''
})

const filter = ref({
  periode: ''
})

function filterData() {
  console.log('Data difilter:', form.value, filter.value)
}
</script>
