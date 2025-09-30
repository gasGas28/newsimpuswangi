<template>
  <div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h2 class="fw-bold mb-0">Laporan Farmasi</h2>
        <p class="text-muted">Laporan berdasarkan unit dan periode</p>
      </div>
    </div>

    <!-- Form Filter -->
    <div class="card shadow-sm mb-4">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Filter Data Laporan Farmasi</h5>
      </div>
      <div class="card-body">
        <form @submit.prevent="filterLaporan">
          <!-- Baris 1 -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Jenis Laporan</label>
              <select v-model="form.laporan" class="form-select" required>
                <option value="">-- Pilih Jenis Laporan --</option>
                <option>Laporan register pasien</option>
                <option>Laporan jumlah pengeluaran langsung</option>
                <option>Laporan jumlah pemakaian harian</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Tanggal Awal</label>
              <input type="date" v-model="form.tanggalAwal" class="form-control" required />
            </div>
          </div>

          <!-- Baris 2 -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Tanggal Akhir</label>
              <input type="date" v-model="form.tanggalAkhir" class="form-control" required />
            </div>
            <div class="col-md-6">
              <label class="form-label">Unit</label>
              <select v-model="form.unit" class="form-select" required>
                <option value="">-- Pilih Unit --</option>
                <option>SEMUA UNIT</option>
                <option>PUSKESMAS</option>
                <option>PUSTU</option>
                <option>POLINDES</option>
                <option>POSYANDU</option>
                <option>POSKESDES</option>
                <option>PUSLING</option>
                <option>POSKESTREN</option>
                <option>PONKESDES</option>
              </select>
            </div>
          </div>

          <!-- Baris 3 -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Sub Unit</label>
              <select v-model="form.subUnit" class="form-select">
                <option value="">-- Pilih Sub Unit --</option>
              </select>
            </div>
            <div class="col-md-6">
              <!-- Kosong sesuai permintaan -->
            </div>
          </div>

          <!-- Tombol -->
          <div class="d-flex justify-content-end gap-2">
            <button type="submit" class="btn btn-success">
              <i class="bi bi-bar-chart-line me-1"></i> Tampilkan Data
            </button>
            <button type="button" class="btn btn-outline-success">
              <i class="bi bi-file-earmark-excel me-1"></i> Download Excel
            </button>
          </div>
        </form>
      </div>
    </div>

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
import 'vue-good-table-next/dist/vue-good-table-next.css'

const form = ref({
  laporan: '',
  tanggalAwal: '',
  tanggalAkhir: '',
  unit: '',
  subUnit: ''
})

const filterLaporan = () => {
  console.log('Filter data', form.value)
}
</script>
