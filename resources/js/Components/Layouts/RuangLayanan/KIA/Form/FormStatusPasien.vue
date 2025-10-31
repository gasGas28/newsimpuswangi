<template>
  <div class="card border-0 shadow-sm rounded-4 p-3">
    <!-- Judul -->
    <h5 class="fw-bold mb-3 text-danger">Rujukan</h5>

    <form @submit.prevent="simpanData">
      <!-- Grid baris utama -->
      <div class="row g-4">
        <!-- === KOLOM KIRI (Form Utama) === -->
        <div class="col-md-6">
          <div class="form-section">
            <div class="mb-2">
              <label class="fw-semibold form-label">Status Pulang</label>
              <select v-model="statusPulang" class="form-select">
                <option value="Berobat Jalan">Berobat Jalan</option>
                <option value="Meninggal">Meninggal</option>
                <option value="Pulang Paksa">Pulang Paksa</option>
                <option value="Rujuk Internal">Rujuk Internal</option>
                <option value="Rujuk Vertikal PCare">Rujuk Vertikal PCare</option>
                <option value="Rujuk Rumah Sakit Bukan BPJS">Rujuk Rumah Sakit Bukan BPJS</option>
                <option value="Rujuk Rumah Sakit">Rujuk Rumah Sakit</option>
              </select>
            </div>

            <div class="mb-2">
              <label class="fw-semibold form-label">Tenaga Medis</label>
              <select v-model="tenagaMedis" class="form-select">
                <option value="Practitioner 1">Practitioner 1</option>
                <option value="Practitioner 2">Practitioner 2</option>
                <option value="Practitioner 3">Practitioner 3</option>
                <option value="Practitioner 4">Practitioner 4</option>
              </select>
            </div>

            <div class="mt-3">
              <button class="btn btn-success">Simpan</button>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <!-- Form Rujuk Internal -->
          <div v-if="statusPulang === 'Rujuk Internal'" class="mb-2">
            <label class="fw-semibold form-label">Poli Rujuk Internal</label>
            <select v-model="poliInternal" class="form-select">
              <option disabled value="">-- Pilih Poli --</option>
              <option value="Umum">Poli Umum</option>
              <option value="Gigi">Poli Gigi</option>
              <option value="KIA">Poli KIA</option>
            </select>
          </div>

          <!-- Rujuk Vertical Pcare dan Rumah Sakit Bukan BPJS-->
          <div>
            <div v-if="statusPulang === 'Rujuk Vertikal PCare' || statusPulang === 'Rujuk Rumah Sakit Bukan BPJS'" class="mb-2">
              <label class="fw-semibold form-label">PPK Rujukan</label>
              <input
                v-model="ppkRujukan"
                readonly
                type="text"
                class="bg-secondary bg-opacity-10 form-control"
              />
            </div>
            <div v-if="statusPulang === 'Rujuk Rumah Sakit Bukan BPJS'" class="mb-2">
              <label class="fw-semibold form-label">Nama Poli</label>
              <input
                v-model="namaPoli"
                readonly
                type="text"
                class="bg-secondary bg-opacity-10 form-control"
              />
            </div>
            <div v-if="statusPulang === 'Rujuk Rumah Sakit Bukan BPJS'" class="mb-2">
              <label class="fw-semibold form-label">Nama Dokter</label>
              <input
                v-model="namaDokter"
                type="text"
                class="form-control"
              />
            </div>
            <div class="mb-2" v-if="statusPulang === 'Rujuk Vertikal PCare'">
              <label class="fw-semibold form-label">Spesialis/SubSpesialis</label>
              <input
                v-model="spesialis"
                readonly
                type="text"
                class="bg-secondary bg-opacity-10 form-control"
              />
            </div>
            <div v-if="statusPulang === 'Rujuk Vertikal PCare' || statusPulang === 'Rujuk Rumah Sakit Bukan BPJS'" class="mb-2">
              <label class="fw-semibold form-label">Catatan</label>
              <input
                v-model="spesialis"
                readonly
                type="text"
                class="bg-secondary bg-opacity-10 form-control"
              />
            </div>
            <div v-if="statusPulang === 'Rujuk Vertikal PCare' || statusPulang === 'Rujuk Rumah Sakit Bukan BPJS'" class="mb-3">
              <label class="fw-semibold form-label">Tanggal Rencana Berkunjung</label>
              <input
                v-model="spesialis"
                readonly
                type="text"
                class="bg-secondary bg-opacity-10 form-control"
              />
            </div>
            <div v-if="statusPulang === 'Rujuk Vertikal PCare' || statusPulang === 'Rujuk Rumah Sakit Bukan BPJS'" class="mb-2">
              <button type="button" class="btn btn-primary">Cari Rujukan</button>
            </div>
          </div>
        </div>
      </div>
    </form>

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
  import { ref } from 'vue';

  const statusPulang = ref('');
  const tenagaMedis = ref('');
  const poliInternal = ref('');
  const namaRS = ref('');
  const dataRujukan = ref([
    {
      keterangan: '',
      poliTujuan: '',
      tenagaMedis: '',
      createdBy: 'user1',
      mulai: '2025-09-22 10:37:48',
      selesai: '',
    },
  ]);

  const simpanData = () => {
    alert('✅ Data berhasil disimpan!');
  };
</script>

<style scoped>
  .card {
    background: #fff;
  }
  .table th,
  .table td {
    font-size: 0.9rem;
  }
</style>
