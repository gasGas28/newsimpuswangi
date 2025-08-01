<template>
  <AppLayout title="Template Components">
    <div class="container px-2 py-2">


      <div class="card">
        <div class="card-body py-3 px-3">
          <h6 class="fw-bold mb-3">Filter Data Laporan Loket</h6>
          <form class="row gx-2 gy-2 align-items-center">
            <div class="col-auto fw-bold">Puskesmas</div>
            <div class="col-auto">
              <select class="form-select form-select-sm">
                <option>WONGSOREJO</option>
              </select>
            </div>

            <div class="col-12"></div>

            <div class="col-auto fw-bold">Laporan</div>
            <div class="col-auto">
              <select class="form-select form-select-sm">
                <option>- Pilih -</option>
              </select>
            </div>

            <div class="col-12"></div>

            <div class="col-auto fw-bold">Tgl Awal</div>
            <div class="col-auto">
              <input type="date" class="form-control form-control-sm" />
            </div>

            <div class="col-12"></div>

            <div class="col-auto fw-bold">Tgl Akhir</div>
            <div class="col-auto">
              <input type="date" class="form-control form-control-sm" />
            </div>

            <div class="col-12"></div>

            <div class="col-auto fw-bold">Unit</div>
            <div class="col-auto">
              <select class="form-select form-select-sm">
                <option>- Pilih -</option>
              </select>
            </div>

            <div class="col-12"></div>

            <div class="col-auto fw-bold">Sub Unit</div>
            <div class="col-auto">
              <select class="form-select form-select-sm">
                <option>- Pilih -</option>
              </select>
            </div>

            <div class="col-12"></div>

            <div class="col-auto fw-bold">Desa</div>
            <div class="col-auto">
              <select class="form-select form-select-sm">
                <option>- SEMUA -</option>
              </select>
            </div>

            <div class="col-12 mt-2">
              <button type="button" class="btn btn-sm btn-primary me-2">
                <i class="bi bi-printer"></i> Tampilkan Data
              </button>
              <button type="button" class="btn btn-sm btn-info text-white">
                <i class="bi bi-file-earmark-excel"></i> Download Excel
              </button>
            </div>
          </form>
        </div>
      </div>


    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Components/Layouts/AppLayouts.vue'
import { Link } from '@inertiajs/vue3'
</script>
