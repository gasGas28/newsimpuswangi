<template>
  <AppLayout title="Template Components">
    <div class="container py-4" style="font-family: 'Segoe UI', sans-serif;">

      <div class="card mb-4 shadow-sm">
        <div class="card-body">
          <h5 class="fw-bold text-white px-3 py-2 rounded mb-4" style="background-color: #0d6efd;">
            Filter Data Laporan Laboratorium
          </h5>

          <form>
            <!-- Puskesmas -->
            <div class="row mb-3">
              <label class="col-md-2 col-form-label fw-semibold">Puskesmas</label>
              <div class="col-md-6">
                <select class="form-select">
                  <option>WONGSOREJO</option>
                </select>
              </div>
            </div>

            <!-- Laporan -->
            <div class="row mb-3">
              <label class="col-md-2 col-form-label fw-semibold">Laporan</label>
              <div class="col-md-6">
                <select class="form-select">
                  <option>- Pilih -</option>
                </select>
              </div>
            </div>

            <!-- Tgl Awal -->
            <div class="row mb-3">
              <label class="col-md-2 col-form-label fw-semibold">Tgl Awal</label>
              <div class="col-md-6">
                <input type="date" class="form-control" />
              </div>
            </div>

            <!-- Tgl Akhir -->
            <div class="row mb-3">
              <label class="col-md-2 col-form-label fw-semibold">Tgl Akhir</label>
              <div class="col-md-6">
                <input type="date" class="form-control" />
              </div>
            </div>

            <!-- Unit -->
            <div class="row mb-3">
              <label class="col-md-2 col-form-label fw-semibold">Unit</label>
              <div class="col-md-6">
                <select class="form-select">
                  <option>- Pilih -</option>
                </select>
              </div>
            </div>

            <!-- Sub Unit -->
            <div class="row mb-4">
              <label class="col-md-2 col-form-label fw-semibold">Sub Unit</label>
              <div class="col-md-6">
                <select class="form-select">
                  <option>- Pilih -</option>
                </select>
              </div>
            </div>

            <!-- Tombol -->
            <div class="row">
              <div class="col-md-8 offset-md-2 d-flex flex-wrap gap-3">
                <!-- Tampilkan Data -->
                <button
                  type="button"
                  class="btn text-white border-0 px-4 py-2 fw-semibold rounded"
                  style="background: linear-gradient(135deg, #28a745, #0dcaf0); transition: 0.3s ease;"
                >
                  <i class="bi bi-printer me-1"></i> Tampilkan Data
                </button>

                <!-- Download Excel -->
                <button
                  type="button"
                  class="btn text-white border-0 px-4 py-2 fw-semibold rounded"
                  style="background: linear-gradient(135deg, #0d6efd, #20c997); transition: 0.3s ease;"
                >
                  <i class="bi bi-download me-1"></i> Download Excel
                </button>
              </div>
            </div>

          </form>
        </div>
      </div>

    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Components/Layouts/AppLayouts.vue'
import '@/../css/laporan-css/form-styles.css'; // sesuaikan path-nya

</script>
