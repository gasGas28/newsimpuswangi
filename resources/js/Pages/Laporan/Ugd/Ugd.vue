<template>
  <AppLayout title="Template Components">
    <div class="container py-4">
      <div class="card mb-4">
        <div class="card-body">
          <div class="mb-3 fw-bold">Filter Data Laporan UGD</div>
          <form>
            <div class="row mb-2">
              <div class="col-sm-2 fw-bold">Puskesmas</div>
              <div class="col-sm-4">
                <select class="form-select">
                  <option selected>WONGSOREJO</option>
                </select>
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-sm-2 fw-bold">Laporan</div>
              <div class="col-sm-4">
                <select class="form-select">
                  <option>- Pilih -</option>
                </select>
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-sm-2 fw-bold">Tgl Awal</div>
              <div class="col-sm-4">
                <input type="text" class="form-control" />
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-sm-2 fw-bold">Tgl Akhir</div>
              <div class="col-sm-4">
                <input type="text" class="form-control" />
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-sm-2 fw-bold">Unit</div>
              <div class="col-sm-4">
                <select class="form-select mb-2">
                  <option>- Pilih -</option>
                </select>
              </div>
              <div class="col-sm-2 fw-bold">Sub Unit</div>
              <div class="col-sm-4">
                <select class="form-select">
                  <option>- Pilih -</option>
                </select>
              </div>
            </div>
            <div class="mt-3">
              <button type="button" class="btn btn-primary me-2">
                <i class="bi bi-printer"></i> Tampilkan Data
              </button>
              <button type="button" class="btn btn-info text-white">
                <i class="bi bi-download"></i> Download Excel
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
