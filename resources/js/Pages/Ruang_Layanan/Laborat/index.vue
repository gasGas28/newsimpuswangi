<template>
  <AppLayouts>
    <div class="card m-4">
    <div class="card-header">
        <div class="card-header bg-primary p-3 text-black d-flex justify-content-between align-items-center rounded">
      <h1 class="m-0 fs-4">Pelayanan Laboratorium</h1>
      <Link  class="btn btn-warning btn-sm">Kembali</Link>
    </div>
    </div>
    <div class="card-body">
    <div class="card-header p-3 text-black d-flex justify-content-between align-items-center rounded">
      <h1 class="m-0 fs-4">Filter Data</h1>
    </div>
    <div class="card-body">
      <form action="">
        <!-- Unit -->
        <div class="mb-3 row">
          <label class="col-md-2 col-form-label fw-semibold">Unit</label>
          <div class="col-md-5">
            <div class="input-group">
              <select class="form-control" name="" id="">
                <option value="">-Pilih-</option>
              </select>
              <select class="form-control" name="" id="">
                <option value="">PUSKESMAS</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Periode -->
         <div class="mb-3 row">
          <label class="col-md-2 col-form-label fw-semibold">Periode</label>
          <div class="col-md-5">
            <input type="text" class="form-control">
          </div>
        </div>
        <button @click.prevent="TampilkanData = true" class="btn btn-primary"><i class="bi bi-search me-2"></i>Tampilkan Data</button>
      </form>
    </div>
    <div class="card mt-4" v-if="TampilkanData === true">
      <div class="card-header bg-white d-flex justify-content-end">
        <form>
          <input type="search" class="form-control" placeholder="Search..." />
        </form>
      </div>
      <div class="card-body p-0">
        <table class="table table-bordered mb-0">
          <thead class="table-light text-center">
            <tr>
              <th>NO</th>
              <th>Nama Pasien</th>
              <th>Alamat</th>
              <th>Kategori</th>
              <th>Nama Poli</th>
              <th>Alasan di rujuk ke lab</th>
              <th>Status layanan</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in rows" :key="index">
              <td>{{ index + 1 }}</td>
              <td>{{ item.tanggal }}</td>
              <td>{{ item.nomor_mr }}</td>
              <td>{{ item.alamat }}</td>
              <td>{{ item.nomor_bpjs }}</td>
              <td>{{ item.poli }}</td>
              <td></td>
              <td class="text-center">
                <Link  class="text-decoration-none btn btn-sm btn-danger" :href="item.linkTo">Belum Dilayani</Link>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    </div>
    </div>
  </AppLayouts>
</template>

<script setup>
import AppLayouts from '../../../Components/Layouts/AppLayouts.vue';
import { ref } from 'vue';
const TampilkanData = ref(false);
</script>
