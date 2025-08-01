<script setup>
  import AppLayout from '@/Components/Layouts/AppLayouts.vue';
  import { ref } from 'vue';
  import { router } from '@inertiajs/vue3';

  defineOptions({ layout: AppLayout });

  const tanggal = ref(new Date().toISOString().split('T')[0]);
  const kategoriUnit = ref('[ PUSKESMAS ] WONGSOREJO');

  function tampilkanData() {
    // Placeholder logika fetch data
    console.log('Tampilkan data untuk', tanggal.value, kategoriUnit.value);
  }

  function kembali() {
    router.get('/mal-sehat/p3m');
  }
</script>

<template>
  <div class="card shadow-sm border-0">
    <!-- HEADER -->
    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
      <strong>Konseling LROA</strong>
      <button class="btn btn-sm btn-light" @click="kembali">Kembali</button>
    </div>

    <!-- FILTER -->
    <div class="card-body">
      <h6 class="fw-bold mb-3">Filter Data</h6>
      <div class="row mb-3">
        <div class="col-md-4 mb-2">
          <label class="form-label">Tanggal kunjungan</label>
          <input type="date" class="form-control" v-model="tanggal" />
        </div>
        <div class="col-md-5 mb-2">
          <label class="form-label">Kategori Unit</label>
          <select class="form-select" v-model="kategoriUnit">
            <option>[ PUSKESMAS ] WONGSOREJO</option>
            <!-- Tambah opsi lain kalau perlu -->
          </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
          <button class="btn btn-primary w-100" @click="tampilkanData">Tampilkan Data</button>
        </div>
      </div>
    </div>

    <!-- TABEL -->
    <div class="card-body pt-0">
      <table class="table table-bordered table-striped table-hover table-sm">
        <thead class="table-light align-middle text-center">
          <tr>
            <th>TANGGAL<br />NO. URUT</th>
            <th>NO. MR</th>
            <th>NAMA<br />NIK</th>
            <th>ALAMAT<br />KECAMATAN-DESA</th>
            <th>NO. BPJS<br />NO. KUNJUNGAN</th>
            <th>POLI</th>
            <th>STATUS</th>
            <th>ACTION</th>
          </tr>
        </thead>
        <tbody class="align-middle text-center">
          <tr>
            <td colspan="8">No matching records found</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
