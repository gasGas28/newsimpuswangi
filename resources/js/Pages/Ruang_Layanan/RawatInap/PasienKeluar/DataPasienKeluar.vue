<template>
    <AppLayouts>
        <div class="card">
        <div class="card-header bg-success p-3 text-white d-flex justify-content-between align-items-center rounded">
        <h1 class="m-0 fs-4">RANAP PASIEN KELUAR</h1>
        <div class="d-flex gap-3">
            <Link :href="route('ruang-layanan.umum')" class="btn text-white btn-warning btn-sm">Pasien Batal Keluar</Link>
            <Link :href="route('ruang-layanan.umum')" class="btn text-white btn-primary btn-sm">Kembali</Link>
         </div>
      </div>
        <div class="card-body m-4 shadow-sm">
      <div class="card-header d-flex justify-content-between align-items-center bg-light">
        <div>
          <label class="me-2">Show</label>
          <select class="form-select d-inline w-auto" style="width: 80px">
            <option>10</option>
            <option>25</option>
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
              <th>Tanggal</th>
              <th>NO.MR</th>
              <th>NAMA NIM</th>
              <th>ALAMAT KECAMATAN-DESA</th>
              <th>NO. BPJS NO. KUNJUNGAN</th>
              <th>Unit</th>
              <th>KAMAR BED</th>
              <th>JML. HARI RAWAT</th>
              <th>ACTION</th>
            </tr>
          </thead>
          <tbody>
            <!-- <tr v-for="(item, index) in rows" :key="index">
              <td>{{ index + 1 }}</td>
              <td>{{ item.tanggal }}</td>
              <td>{{ item.nomor_mr }}</td>
              <td>{{ item.alamat }}</td>
              <td>{{ item.nomor_bpjs }}</td>
              <td>{{ item.poli }}</td>
              <td class="text-center">
                <Link  class="text-decoration-none btn btn-sm btn-danger" :href="item.linkTo">Belum Dilayani</Link>
              </td>
            </tr> -->
          </tbody>
        </table>
      </div>
    </div>

        </div>
    </AppLayouts>
   
</template>

<script setup>
import AppLayouts from '../../../../Components/Layouts/AppLayouts.vue';
</script>