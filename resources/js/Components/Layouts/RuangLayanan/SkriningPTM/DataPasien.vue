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
          <h3 v-for="pasien in DataPasien" :key="pasien.ID" class="mb-1 fw-bold">
            {{ pasien.NAMA_LGKP }}
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
          <p v-for="pasien in DataPasien" :key="pasien.ID" class="mb-0 fw-semibold">
            L {{ pasien.umur }} Tahun - {{ pasien.umur_bulan }} Bulan - {{ pasien.umur_hari }} Hari
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
          <p v-for="address in DataPasien" :key="address.ID" class="mb-0 fw-semibold">
            {{ address.alamat }} RT {{ address.no_rt }} RW {{ address.no_rw }} Kel.
            {{ address.nama_kel }} Kec. {{ address.nama_kec }} <br />{{ address.nama_kab }} Prov.
            {{ address.nama_prop }}
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
          <p v-for="poli in DataPasien" :key="poli.ID" class="mb-0 fw-semibold">
            Kunjungan Sakit ({{ poli.nmPoli }})
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
          <p v-for="tanggal in DataPasien" :key="tanggal.ID" class="mb-0 fw-semibold">
            {{ tanggal.tglKunjungan }}
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
          <p v-for="no in DataPasien" :key="no.ID" class="mb-0 fw-semibold">
            {{ no.NO_MR }} / {{ no.NIK }}
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
          <p class="mb-0 fw-semibold">BPJS-9876543210 / Klinik Sehat</p>
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
    DataPasien: Array,
    backRoute: String,
  });
</script>
