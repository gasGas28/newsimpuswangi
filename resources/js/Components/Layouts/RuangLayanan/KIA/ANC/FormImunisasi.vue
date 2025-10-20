<template>
  <div class="container-fluid p-3">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="fw-bold mb-0">Imunisasi</h5>
      <button class="btn btn-primary">Lihat Riwayat Imunisasi</button>
    </div>

    <div class="row">
      <!-- Form Imunisasi -->
      <div class="col-md-6">
        <form @submit.prevent="simpanImunisasi" class="border rounded p-3 bg-light">
          <!-- Tanggal Imunisasi -->
          <div class="mb-3 row align-items-center">
            <label class="col-sm-4 col-form-label fw-semibold">Tgl Imunisasi</label>
            <div class="col-sm-8">
              <input
                type="datetime-local"
                class="form-control"
                v-model="form.tanggal"
              />
            </div>
          </div>

          <!-- Jenis Imunisasi -->
          <div class="mb-3 row align-items-center">
            <label class="col-sm-4 col-form-label fw-semibold">Jenis Imunisasi<span class="text-danger">*</span></label>
            <div class="col-sm-8">
              <select class="form-select" v-model="form.jenis">
                <option value="">-- Pilih Jenis Imunisasi --</option>
                <option v-for="item in daftarImunisasi" :key="item" :value="item">
                  {{ item }}
                </option>
              </select>
            </div>
          </div>

          <!-- Tombol Simpan -->
          <div class="text-start">
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Tabel Riwayat -->
    <div class="mt-4">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>No</th>
            <th>Tanggal Imunisasi</th>
            <th>Umur</th>
            <th>Imunisasi</th>
            <th>Created By</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in dataImunisasi" :key="index">
            <td>{{ index + 1 }}</td>
            <td>{{ item.tanggal }}</td>
            <td>{{ item.umur }}</td>
            <td>{{ item.jenis }}</td>
            <td>{{ item.createdBy }}</td>
            <td>
              <button class="btn btn-danger btn-sm" @click="hapusData(index)">Hapus</button>
            </td>
          </tr>
          <tr v-if="dataImunisasi.length === 0">
            <td colspan="6" class="text-center text-muted">Belum ada data imunisasi</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  DataPasien: Array,
});

const form = ref({
  tanggal: new Date().toISOString().slice(0, 16),
  jenis: '',
  usia: '',
})

// update usia ketika props.DataPasien sudah tersedia
watch(
  () => props.DataPasien,
  (newVal) => {
    if (Array.isArray(newVal) && newVal.length > 0 && newVal[0].umur !== undefined) {
      form.value.usia = newVal[0].umur
    }
  },
  { immediate: true }
)

const daftarImunisasi = [
  'HB < JAM',
  'BCG',
  'Polio',
  'Campak',
  'DPT'
]

const dataImunisasi = ref([])

function simpanImunisasi() {
  if (!form.value.jenis) {
    alert('Jenis imunisasi wajib diisi!')
    return
  }

  dataImunisasi.value.push({
    tanggal: form.value.tanggal,
    umur: form.value.usia,
    jenis: form.value.jenis,
    createdBy: 'Petugas A'
  })

  alert('Data imunisasi berhasil disimpan!')
  form.value.jenis = ''
}

function hapusData(index) {
  dataImunisasi.value.splice(index, 1)
}
</script>

<style scoped>
.table th, .table td {
  vertical-align: middle;
}

form {
  max-width: 500px;
}

button.btn-primary {
  background-color: #428bca;
  border-color: #357ebd;
}

button.btn-primary:hover {
  background-color: #3071a9;
}
</style>
