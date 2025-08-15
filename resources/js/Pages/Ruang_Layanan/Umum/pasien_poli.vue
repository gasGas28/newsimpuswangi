<template>
  <AppLayouts>
    <div class="card m-4 p-3 shadow-sm border-0">
      <!-- Header -->
      <div class="card-header bg-primary p-3 text-white d-flex justify-content-between align-items-center rounded">
        <h1 class="m-0 fs-4">Poli UMUM BP</h1>
        <Link :href="route('ruang-layanan.umum')" class="btn text-white btn-warning btn-sm">Kembali</Link>
      </div>

      <!-- Filter Form -->
      <div class="card mt-4 shadow-sm">
        <div class="card-header bg-light">
          <h5 class="m-0">Filter Data</h5>
        </div>
        <div class="card-body">
          <form>
            <div class="mb-3 row">
              <label for="tanggal_kunjungan" class="col-md-3 col-form-label fw-semibold"
                >Tanggal Kunjungan</label
              >
              <div class="col-md-5">
                <input class="form-control" type="date" id="tanggal_kunjungan" />
              </div>
            </div>

            <div class="mb-3 row">
              <label for="kategori_unit" class="col-md-3 col-form-label fw-semibold"
                >Kategori Unit</label
              >
              <div class="col-md-5">
                <select class="form-select" id="kategori_unit">
                  <option v-for="angka in 10" :key="angka">[ PUSKESMAS ] TEGALLDIMO</option>
                </select>
              </div>
            </div>

            <div class="row">
              <div class="col-md-5 offset-md-3 d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Tampilkan Data</button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Data Table -->
      <div class="card mt-4 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center bg-light">
          <div>
            <label class="me-2">Show</label>
            <select class="form-select d-inline w-auto" style="width: 80px">
              <option v-for="angka in 4" :key="angka">10</option>
            </select>
            <span class="ms-2">entries</span>
          </div>
          <form>
            <input type="search" class="form-control" placeholder="Search..." />
          </form>
        </div>
        <div class="card-body p-0">
          <table class="table table-bordered mb-0">
            <thead class="table-light text-center">
              <tr>
                <th>NOMOR</th>
                <th>TANGGAL NO. URUT BPJS</th>
                <th>NO.MR</th>
                <th>ALAMAT KECAMATAN-DESA</th>
                <th>NO. BPJS NO. KUNJUNGAN</th>
                <th>POLI</th>
                <th>ACTION</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>1</td>
                <td>1</td>
                <td>1</td>
                <td>1</td>
                <td>1</td>
                <td class="text-center"><Link class="text-decoration-none btn btn-sm btn-danger" :href="route('ruang-layanan-umum.pelayanan')">Belum Dilayani</Link></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AppLayouts>
</template>
<script setup>
  import { Link } from '@inertiajs/vue3';
  import AppLayouts from '../../../Components/Layouts/AppLayouts.vue';
</script>
