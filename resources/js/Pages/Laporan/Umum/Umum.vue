<template>
  <AppLayout title="Template Components">
    <div class="container py-4">

      <!-- ✅ Tambahan: Form Filter Laporan Umum -->
      <div class="card mb-4">
        <div class="card-header fw-bold">
          Filter Data Laporan Umum
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-3 fw-bold">Puskesmas</div>
            <div class="col-md-9 mb-2">
              <select class="form-select form-select-sm">
                <option>WONGSOREJO</option>
              </select>
            </div>

            <div class="col-md-3 fw-bold">Laporan</div>
            <div class="col-md-9 mb-2">
              <select class="form-select form-select-sm">
                <option>- PILIH -</option>
              </select>
            </div>

            <div class="col-md-3 fw-bold">Tgl Awal</div>
            <div class="col-md-9 mb-2">
              <input type="date" class="form-control form-control-sm" />
            </div>

            <div class="col-md-3 fw-bold">Tgl Akhir</div>
            <div class="col-md-9 mb-2">
              <input type="date" class="form-control form-control-sm" />
            </div>

            <div class="col-md-3 fw-bold">Unit</div>
            <div class="col-md-9 mb-2">
              <select class="form-select form-select-sm">
                <option>- Pilih -</option>
              </select>
            </div>

            <div class="col-md-3 fw-bold">Sub Unit</div>
            <div class="col-md-9 mb-2">
              <select class="form-select form-select-sm">
                <option>- Pilih -</option>
              </select>
            </div>
          </div>

          <hr />
          <div class="mt-2">
            <button class="btn btn-sm btn-primary me-2">
              <i class="bi bi-printer"></i> Tampilkan Data
            </button>
            <button class="btn btn-sm btn-info text-white">
              <i class="bi bi-download"></i> Download
            </button>
          </div>
        </div>
      </div>


    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Components/Layouts/AppLayouts.vue'
import { Link } from '@inertiajs/vue3'
</script>
