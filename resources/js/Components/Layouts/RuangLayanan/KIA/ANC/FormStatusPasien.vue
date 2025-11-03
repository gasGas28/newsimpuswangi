<template>
  <div class="card border-0 shadow-sm rounded-4 p-3">
    <!-- Judul -->
    <h6 class="fw-bold mb-3 text-danger">Rujukan</h6>

    <!-- Form -->
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Status Pulang</label>
        <select v-model="statusPulang" class="form-select">
          <option>Berobat Jalan</option>
          <option>Dirawat</option>
          <option>Rujuk</option>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Tenaga Medis</label>
        <select v-model="tenagaMedis" class="form-select">
          <option value="">Practitioner 1</option>
          <option>Practitioner 2</option>
          <option>Practitioner 3</option>
          <option>Practitioner 4</option>
        </select>
      </div>

      <div class="col-12">
        <button class="btn btn-success btn-sm fw-semibold px-3" @click="simpanData">
          Simpan
        </button>
      </div>
    </div>

    <hr class="my-4" />

    <!-- Tabel Data -->
    <div class="table-responsive">
      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th>No</th>
            <th>Asal Poli</th>
            <th>Keterangan</th>
            <th>Poli Tujuan</th>
            <th>Tenaga Medis</th>
            <th>Created By</th>
            <th>Mulai Melayani</th>
            <th>Selesai Melayani</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in dataRujukan" :key="index">
            <td>{{ index + 1 }}</td>
            <td><span class="badge bg-success">Umum</span></td>
            <td>{{ item.keterangan }}</td>
            <td>{{ item.poliTujuan }}</td>
            <td>{{ item.tenagaMedis }}</td>
            <td>{{ item.createdBy }}</td>
            <td>{{ item.mulai }}</td>
            <td>{{ item.selesai }}</td>
            <td>
              <button class="btn btn-outline-secondary btn-sm">Poli awal</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";

const statusPulang = ref("Berobat Jalan");
const tenagaMedis = ref("");
const dataRujukan = ref([
  {
    keterangan: "",
    poliTujuan: "",
    tenagaMedis: "",
    createdBy: "user1",
    mulai: "2025-09-22 10:37:48",
    selesai: "",
  },
]);

const simpanData = () => {
  alert("Data berhasil disimpan!");
};
</script>

<style scoped>
.card {
  background: #fff;
}
h6 {
  color: #333;
}
.table th,
.table td {
  font-size: 0.9rem;
}
</style>
