<template>
  <AppLayout title="Template Components">
    <div class="container px-2 py-2">
      <!-- Filter Data Rapat -->
      <div class="card">
        <div class="card-body py-3 px-3">
          <h6 class="fw-bold mb-3">
            <span
              class="d-inline-block w-100 px-2 py-1 text-white"
              style="background-color: #0d6efd"
            >
              Filter Data Laporan Sehat
            </span>
          </h6>

          <div class="row gx-2 gy-1">
            <div class="col-md-6">
              <div class="mb-1">
                <label class="form-label fw-bold small mb-1">Puskesmas</label>
                <select class="form-select form-select-sm">
                  <option>WONGSOREJO</option>
                </select>
              </div>
              <div class="mb-1">
                <label class="form-label fw-bold small mb-1">Laporan</label>
                <select class="form-select form-select-sm">
                  <option>- PILIH -</option>
                </select>
              </div>
              <div class="mb-1">
                <label class="form-label fw-bold small mb-1">Tgl Awal</label>
                <input type="date" class="form-control form-control-sm" />
              </div>
              <div class="mb-1">
                <label class="form-label fw-bold small mb-1">Tgl Akhir</label>
                <input type="date" class="form-control form-control-sm" />
              </div>
              <div class="mb-1">
                <label class="form-label fw-bold small mb-1">Unit</label>
                <select class="form-select form-select-sm">
                  <option>- Pilih -</option>
                </select>
              </div>
              <div class="mb-1">
                <label class="form-label fw-bold small mb-1">Sub Unit</label>
                <select class="form-select form-select-sm">
                  <option>- Pilih -</option>
                </select>
              </div>
            </div>
          </div>

          <div class="mt-2">
            <button class="btn btn-sm btn-primary me-2">
              <i class="bi bi-printer"></i> Tampilkan Data
            </button>
            <button class="btn btn-sm btn-info text-white">
              <i class="bi bi-download"></i> Download Excel
            </button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
  import AppLayout from '@/Components/Layouts/AppLayouts.vue';
  import { Link } from '@inertiajs/vue3';
</script>
