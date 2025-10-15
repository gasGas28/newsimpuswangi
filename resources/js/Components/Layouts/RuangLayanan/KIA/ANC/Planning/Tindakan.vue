<template>
  <h5 class="fw-semibold text-danger">Tindakan</h5>
  <div class="card border-0 shadow-sm rounded-4 p-3">
    <div class="row mb-2">
      <label class="col-sm-2 col-form-label">Kode Tindakan</label>
      <div class="col-sm-6 d-flex">
        <input
          type="text"
          class="form-control"
          disabled
          v-model="kodeTindakan"
          placeholder="Masukkan kode tindakan"
        />
        <button class="btn btn-info btn-sm ms-2 text-white">Cari</button>
        <button class="btn btn-danger btn-sm ms-1">Del</button>
      </div>
    </div>

    <div class="row mb-2">
      <label class="col-sm-2 col-form-label">Nama Tindakan</label>
      <div class="col-sm-6">
        <textarea disabled class="form-control" rows="1" v-model="namaTindakan"></textarea>
      </div>
    </div>

    <div class="row mb-2">
      <label class="col-sm-2 col-form-label">Nama Tindakan (Ind)</label>
      <div class="col-sm-6">
        <textarea disabled class="form-control" rows="1" v-model="namaTindakanInd"></textarea>
      </div>
    </div>

    <div class="row mb-3">
      <label class="col-sm-2 col-form-label">Keterangan</label>
      <div class="col-sm-6">
        <textarea class="form-control" rows="1" v-model="keterangan"></textarea>
      </div>
    </div>

    <div class="row mb-4">
      <div class="col-sm-2"></div>
      <div class="col-sm-6">
        <button class="btn btn-primary btn-sm" @click="simpanData">Simpan</button>
      </div>
    </div>

    <hr />

    <div class="table-responsive">
      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Nama Tindakan</th>
            <th>Peraturan</th>
            <th>Harga</th>
            <th>Bayar</th>
            <th>Poli</th>
            <th>Keterangan</th>
            <th>Ket Gigi</th>
            <th>Created By</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, i) in dataTindakan" :key="i">
            <td>{{ i + 1 }}</td>
            <td>{{ item.kode }}</td>
            <td>{{ item.nama }}</td>
            <td>{{ item.peraturan }}</td>
            <td>{{ item.harga }}</td>
            <td>{{ item.bayar }}</td>
            <td>{{ item.poli }}</td>
            <td>{{ item.keterangan }}</td>
            <td>{{ item.ketGigi }}</td>
            <td>{{ item.createdBy }}</td>
            <td>
              <button class="btn btn-outline-secondary btn-sm">Edit</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
  import { ref } from 'vue';

  const kodeTindakan = ref('');
  const namaTindakan = ref('');
  const namaTindakanInd = ref('');
  const keterangan = ref('');

  const dataTindakan = ref([
    {
      kode: 'TDK001',
      nama: 'Pemeriksaan Umum',
      peraturan: 'Standar',
      harga: '50.000',
      bayar: 'Tunai',
      poli: 'Umum',
      keterangan: '',
      ketGigi: '-',
      createdBy: 'user1',
    },
  ]);

  const simpanData = () => {
    alert('Data tindakan berhasil disimpan!');
  };
</script>

<style scoped>
  .card {
    background: #fff;
  }
  .table th,
  .table td {
    font-size: 0.88rem;
  }
  button {
    white-space: nowrap;
  }
</style>
