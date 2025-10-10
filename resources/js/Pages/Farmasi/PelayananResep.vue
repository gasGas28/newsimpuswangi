<template>
  <div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h2 class="fw-bold mb-0">Pelayanan Resep dari Poli</h2>
        <p class="text-muted">Daftar pasien yang menerima resep dari poli layanan</p>
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
            <div class="col-md-4">
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

            <div class="col-md-4">
              <label class="form-label">Sub Unit</label>
              <select v-model="form.subUnit" class="form-select">
                <option value="">-- Pilih Sub Unit --</option>
                <option>PUSKESMAS WONGSOREJO</option>
                <option>PUSKESMAS BANGOREJO</option>
              </select>
            </div>

            <div class="col-md-4">
              <label class="form-label">Periode</label>
              <input type="date" v-model="form.periode" class="form-control" />
            </div>
          </div>

           <div class="text-end">
            <button type="submit" class="btn btn-success">
              <i class=""></i> Tampilkan Data
            </button>
          </div>

        </form>
      </div>
    </div>

    <div class="mb-3">
      <h5 class="fw-bold">Daftar Pasien Farmasi</h5>
    </div>

    <!-- Tabel Pasien -->
    <vue-good-table
      :columns="columns"
      :rows=[]
      :search-options="{ enabled: true }"
      :pagination-options="{ enabled: true, perPage: 10 }"
    />

    <!-- Tombol Kembali -->
    <div class="mt-4 text-start">
      <a href="/farmasi" class="btn btn-secondary">
        <i class=""></i> Kembali
      </a>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { VueGoodTable } from 'vue-good-table-next'
import 'vue-good-table-next/dist/vue-good-table-next.css'

const columns = [
  { label: 'No', field: 'no' },
  { label: 'Created', field: 'nama' },
  { label: 'No RM', field: 'nomor' },
  { label: 'Alamat', field: 'address' },
  { label: 'Kategori', field: 'kategori' },
  { label: 'Diagnosa', field: 'diagnosa' },
  { label: 'Sample', field: 'sample' },
  { label: 'Status Resep', field: 'status-resep' },
  { label: 'Action', field: 'action' },
]

const form = ref({
  unit: '',
  subUnit: '',
  periode: ''
})

function filterData() {
  console.log('Data difilter:', form.value)
}
</script>

