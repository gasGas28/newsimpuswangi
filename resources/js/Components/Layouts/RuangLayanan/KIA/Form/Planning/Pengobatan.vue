<template>
  <h5 class="fw-semibold text-danger">Pengobatan</h5>
  <div class="card border-0 shadow-sm rounded-4 p-3">
    <!-- Form Input -->
    <div class="row mb-2 align-items-center">
      <label class="fw-semibold col-sm-2 col-form-label">Puyer / Bukan Puyer</label>
      <div class="col-sm-3">
        <select class="form-select" v-model="jenisPuyer">
          <option value="Puyer">PUYER</option>
          <option value="Bukan Puyer">BUKAN PUYER</option>
        </select>
      </div>
    </div>

    <!-- Form ini hanya muncul kalau jenisPuyer = 'Puyer' -->
    <div v-if="jenisPuyer === 'Puyer'">
      <div class="row mb-2 align-items-center">
        <label class="fw-semibold col-sm-2 col-form-label">Jumlah Puyer</label>
        <div class="col-sm-2">
          <input type="number" class="form-control" v-model="jumlahPuyer" />
        </div>
      </div>

      <div class="row mb-2 align-items-center">
        <label class="fw-semibold col-sm-2 col-form-label">Dosis Pakai Puyer</label>
        <div class="col-sm-5 d-flex align-items-center">
          <input type="number" class="form-control w-auto" v-model="dosisPerHari" />
          <span class="fw-semibold mx-2">x Sehari, setiap</span>
          <input type="number" class="form-control w-auto" v-model="intervalJam" />
          <span class="fw-semibold ms-2">Jam Sekali</span>
        </div>
      </div>

      <div class="row mb-2">
        <label class="fw-semibold col-sm-2 col-form-label">Waktu</label>
        <div class="col-sm-6 d-flex align-items-center">
          <div class="fw-semibold form-check me-3" v-for="w in ['pagi', 'siang', 'malam']" :key="w">
            <input class="form-check-input" type="checkbox" :value="w" v-model="waktu" />
            <label class="form-check-label text-capitalize">{{ w }}</label>
          </div>
        </div>
      </div>

      <div class="row mb-3">
        <label class="fw-semibold col-sm-2 col-form-label">Kondisi</label>
        <div class="col-sm-6 d-flex align-items-center">
          <div class="fw-semibold form-check me-3" v-for="k in ['sebelum makan', 'saat makan', 'setelah makan']" :key="k">
            <input class="form-check-input" type="checkbox" :value="k" v-model="kondisi" />
            <label class="form-check-label text-capitalize">{{ k }}</label>
          </div>
        </div>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-sm-2"></div>
      <div class="col-sm-6">
        <button class="btn btn-success btn-sm" @click="simpanResep">Simpan Resep</button>
      </div>
    </div>

    <!-- Table -->
    <div class="table-responsive mt-3">
      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th>Poli</th>
            <th>Jenis / Nama Puyer</th>
            <th>Jumlah Obat / Puyer</th>
            <th>Dosis Pakai</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in dataResep" :key="index">
            <td>{{ item.poli }}</td>
            <td>{{ item.jenis }}</td>
            <td>{{ item.jumlah }}</td>
            <td>{{ item.dosis }}</td>
            <td>
              <button class="btn btn-outline-primary me-2 btn-sm" @click="hapusResep(index)">
                Tambah
              </button>
              <button class="btn btn-outline-danger btn-sm" @click="hapusResep(index)">
                Hapus
              </button>
            </td>
          </tr>
          <tr v-if="dataResep.length === 0">
            <td colspan="5" class="text-center text-muted">Belum ada resep</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const jenisPuyer = ref('Bukan Puyer')
const jumlahPuyer = ref('')
const dosisPerHari = ref('')
const intervalJam = ref('')
const waktu = ref([])
const kondisi = ref([])
const dataResep = ref([])

// 👇 Watcher untuk reset form ketika bukan puyer
watch(jenisPuyer, (val) => {
  if (val === 'Bukan Puyer') {
    jumlahPuyer.value = ''
    dosisPerHari.value = ''
    intervalJam.value = ''
    waktu.value = []
    kondisi.value = []
  }
})

const simpanResep = () => {
  dataResep.value.push({
    poli: 'KIA',
    jenis: jenisPuyer.value,
    jumlah: jenisPuyer.value === 'Puyer' ? jumlahPuyer.value : '-',
    dosis: jenisPuyer.value === 'Puyer'
      ? `${dosisPerHari.value}x / ${intervalJam.value} jam`
      : '-',
  })
  alert('Resep berhasil disimpan!')
}

const hapusResep = (index) => {
  dataResep.value.splice(index, 1)
}
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
