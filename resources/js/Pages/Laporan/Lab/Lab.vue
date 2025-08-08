<template>
  <AppLayout title="Template Components">
    <div class="container py-4">
      <div class="card mb-4">
        <div class="card-body">
          <h5 class="mb-3 fw-bold">Filter Data Laporan Laboratorium</h5>
          <form>
            <div class="row mb-2 align-items-center">
              <div class="col-md-2 fw-bold">Puskesmas</div>
              <div class="col-md-5">
                <select class="form-select">
                  <option>WONGSOREJO</option>
                </select>
              </div>
            </div>
            <div class="row mb-2 align-items-center">
              <div class="col-md-2 fw-bold">Laporan</div>
              <div class="col-md-5">
                <select class="form-select">
                  <option>- Pilih -</option>
                </select>
              </div>
            </div>
            <div class="row mb-2 align-items-center">
              <div class="col-md-2 fw-bold">Tgl Awal</div>
              <div class="col-md-5">
                <input type="text" class="form-control" />
              </div>
            </div>
            <div class="row mb-2 align-items-center">
              <div class="col-md-2 fw-bold">Tgl Akhir</div>
              <div class="col-md-5">
                <input type="text" class="form-control" />
              </div>
            </div>
            <div class="row mb-2 align-items-center">
              <div class="col-md-2 fw-bold">Unit</div>
              <div class="col-md-5">
                <select class="form-select">
                  <option>- Pilih -</option>
                </select>
              </div>
            </div>
            <div class="row mb-3 align-items-center">
              <div class="col-md-2 fw-bold">Sub Unit</div>
              <div class="col-md-5">
                <select class="form-select">
                  <option>- Pilih -</option>
                </select>
              </div>
            </div>
            <div>
              <button type="button" class="btn btn-primary me-2">
                <i class="bi bi-printer me-1"></i> Tampilkan Data
              </button>
              <button type="button" class="btn btn-info text-white">
                <i class="bi bi-download me-1"></i> Download Excel
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
  import AppLayout from '@/Components/Layouts/AppLayouts.vue';
  import { Link } from '@inertiajs/vue3';
</script>
