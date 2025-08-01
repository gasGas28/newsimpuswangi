<template>
  <AppLayout title="Template Components">
    <div class="container py-4">
      <!-- Tambahan Form Filter Data Laporan Sanitasi -->
      <div class="card mb-4">
        <div class="card-header fw-bold">Filter Data Laporan Sanitasi</div>
        <div class="card-body">
          <form>
            <div class="row mb-2">
              <div class="col-md-2 fw-bold">Puskesmas</div>
              <div class="col-md-4">
                <select class="form-select">
                  <option>WONGSOREJO</option>
                </select>
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-md-2 fw-bold">Laporan</div>
              <div class="col-md-4">
                <select class="form-select">
                  <option>- Pilih -</option>
                </select>
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-md-2 fw-bold">Tgl Awal</div>
              <div class="col-md-4">
                <input type="text" class="form-control" placeholder="" />
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-md-2 fw-bold">Tgl Akhir</div>
              <div class="col-md-4">
                <input type="text" class="form-control" placeholder="" />
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-md-2 fw-bold">Unit</div>
              <div class="col-md-4">
                <select class="form-select">
                  <option>- Pilih -</option>
                </select>
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-md-2 fw-bold">Sub Unit</div>
              <div class="col-md-4">
                <select class="form-select">
                  <option>- Pilih -</option>
                </select>
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-md-2 fw-bold">Desa</div>
              <div class="col-md-4">
                <select class="form-select">
                  <option>- SEMUA -</option>
                </select>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-md-6">
                <button type="button" class="btn btn-primary me-2">
                  <i class="bi bi-printer"></i> Tampilkan Data
                </button>
                <button type="button" class="btn btn-info text-white">
                  <i class="bi bi-download"></i> Download Excel
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
      <!-- END: Tambahan -->
    </div>
  </AppLayout>
</template>

<script setup>
  import AppLayout from '@/Components/Layouts/AppLayouts.vue';
  import { Link } from '@inertiajs/vue3';
</script>
