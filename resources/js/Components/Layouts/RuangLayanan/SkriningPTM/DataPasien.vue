<template>
  <div class="bg-white rounded-4 shadow-sm p-4 mb-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <!-- Kiri -->
      <div class="d-flex align-items-center">
        <div class="bg-primary bg-opacity-10 rounded-circle px-2 me-3">
          <i class="bi bi-person-fill text-primary fs-1"></i>
        </div>
        <div>
          <h3 class="mb-1 fw-bold">
            {{ DataPasien.NAMA_LGKP }}
            <span class="text-muted">(-)</span>
          </h3>
        </div>
      </div>

      <!-- Kanan -->
      <div>
        <Link :href="route(backRoute)" class="btn btn-outline-success btn-sm rounded-3 px-3">
          <i class="bi bi-arrow-left-circle me-1"></i>
          Kembali
        </Link>
      </div>
    </div>

    <div class="row g-3">
      <!-- Column 1 -->
      <div class="col-md-4">
        <div class="info-item bg-light bg-opacity-50 p-3 rounded-3 h-100">
          <div class="d-flex align-items-center mb-2">
            <i class="bi bi-calendar3 text-primary me-2"></i>
            <h6 class="mb-0 text-muted">Jk / Umur</h6>
          </div>
          <p class="mb-0 fw-semibold">
            L {{ DataPasien.umur }} Tahun - {{ DataPasien.umur_bulan }} Bulan - {{ DataPasien.umur_hari }} Hari
          </p>
        </div>
      </div>

      <!-- Column 2 -->
      <div class="col-md-4">
        <div class="info-item bg-light bg-opacity-50 p-3 rounded-3 h-100">
          <div class="d-flex align-items-center mb-2">
            <i class="bi bi-geo-alt-fill text-primary me-2"></i>
            <h6 class="mb-0 text-muted">Alamat</h6>
          </div>
          <p class="mb-0 fw-semibold">
            {{ DataPasien.alamat }} RT {{ DataPasien.no_rt }} RW {{ DataPasien.no_rw }} Kel.
            {{ DataPasien.nama_kel }} Kec. {{ DataPasien.nama_kec }} <br />{{ DataPasien.nama_kab }} Prov.
            {{ DataPasien.nama_prop }}
          </p>
        </div>
      </div>

      <!-- Column 3 -->
      <div class="col-md-4">
        <div class="info-item bg-light bg-opacity-50 p-3 rounded-3 h-100">
          <div class="d-flex align-items-center mb-2">
            <i class="bi bi-heart-pulse-fill text-success me-2"></i>
            <h6 class="mb-0 text-muted">Jenis/Poli</h6>
          </div>
          <p class="mb-0 fw-semibold">
            Kunjungan Sakit ({{ DataPasien.nmPoli }})
          </p>
        </div>
      </div>

      <!-- Column 4 -->
      <div class="col-md-4">
        <div class="info-item bg-light bg-opacity-50 p-3 rounded-3 h-100">
          <div class="d-flex align-items-center mb-2">
            <i class="bi bi-calendar-check-fill text-info me-2"></i>
            <h6 class="mb-0 text-muted">Tanggal Kunjungan</h6>
          </div>
          <p class="mb-0 fw-semibold">
            {{ DataPasien.tglKunjungan }}
          </p>
        </div>
      </div>

      <!-- Column 5 -->
      <div class="col-md-4">
        <div class="info-item bg-light bg-opacity-50 p-3 rounded-3 h-100">
          <div class="d-flex align-items-center mb-2">
            <i class="bi bi-credit-card-fill text-warning me-2"></i>
            <h6 class="mb-0 text-muted">No. RM / NIK</h6>
          </div>
          <p class="mb-0 fw-semibold">
            {{ DataPasien.NO_MR }} / {{ DataPasien.NIK }}
          </p>
        </div>
      </div>

      <!-- Column 6 -->
      <div class="col-md-4">
        <div class="info-item bg-light bg-opacity-50 p-3 rounded-3 h-100">
          <div class="d-flex align-items-center mb-2">
            <i class="bi bi-credit-card-fill text-warning me-2"></i>
            <h6 class="mb-0 text-muted">No. BPJS/Provider</h6>
          </div>
          <p class="mb-0 fw-semibold">
            {{ DataPasien.NO_BPJS }} / {{ DataPasien.PROVIDER }}
          </p>
        </div>
      </div>
    </div>
  </div>
  <div class="bg-white shadow-sm p-3 rounded-3 mb-3 d-flex align-items-center">
    <h5 class="fw-semibold text-danger mb-1">{{ title }}</h5>
  </div>
</template>

<script setup>
  import { Link } from '@inertiajs/vue3';

  const props = defineProps({
    title: String,
    DataPasien: Object,
    backRoute: String,
  });
</script>
