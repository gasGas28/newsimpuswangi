<template>
  <AppLayout title="Template Components">
    <div class="container py-4">
      <div class="row mb-4">
        <div class="col">
          <h1 class="fw-bold">Halaman Kunjungan sehat</h1>
          <p class="text-muted">Reusable components for your application</p>
        </div>
      </div>
      <!-- Tambahan form filter seperti gambar -->
      <div class="card mb-4">
        <div class="card-body">
          <h5 class="card-title fw-bold">Filter Data Laporan Sehat</h5>
          <div class="row">
            <div class="col-md-6">
              <div class="mb-2">
                <label class="form-label fw-bold">Puskesmas</label>
                <select class="form-select">
                  <option>WONGSOREJO</option>
                </select>
              </div>
              <div class="mb-2">
                <label class="form-label fw-bold">Laporan</label>
                <select class="form-select">
                  <option>- PILIH -</option>
                </select>
              </div>
              <div class="mb-2">
                <label class="form-label fw-bold">Tgl Awal</label>
                <input type="text" class="form-control"/>
              </div>
              <div class="mb-2">
                <label class="form-label fw-bold">Tgl Akhir</label>
                <input type="text" class="form-control"/>
              </div>
              <div class="mb-2">
                <label class="form-label fw-bold">Unit</label>
                <select class="form-select">
                  <option>- Pilih -</option>
                </select>
              </div>
              <div class="mb-2">
                <label class="form-label fw-bold">Sub Unit</label>
                <select class="form-select">
                  <option>- Pilih -</option>
                </select>
              </div>
            </div>
          </div>
          <div class="mt-3">
            <button class="btn btn-primary me-2">
              <i class="bi bi-printer"></i> Tampilkan Data
            </button>
            <button class="btn btn-info text-white">
              <i class="bi bi-download"></i> Download Excel
            </button>
          </div>
        </div>
      </div>

      <div class="row g-4">
        <div class="col-md-4">
          <div class="card h-100 shadow-sm">
            <div class="card-body">
              <h5 class="card-title fw-bold">Buttons</h5>
              <p class="card-text text-muted">Various button styles and variants.</p>
              <Link href="/template/button" class="btn btn-outline-primary">View Buttons</Link>
            </div>
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