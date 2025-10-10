<template>
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h2 class="fw-bold mb-0">Master Obat</h2>
        <p class="text-muted">Daftar seluruh obat yang tersedia di farmasi</p>
      </div>
      <button class="btn btn-primary" @click="showModal = true">
        <i class=""></i> Tambah Obat
      </button>
    </div>

    <vue-good-table
      :columns="columns"
      :rows="obatList"
      :search-options="{ enabled: true }"
      :pagination-options="{ enabled: true, perPage: 10 }"
    />

    <!-- Modal Tambah Obat -->
    <div v-if="showModal" class="modal fade show d-block" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Tambah Obat</h5>
            <button type="button" class="btn-close" @click="showModal = false"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="tambahObat">
              <div class="mb-3">
                <label class="form-label">Kode Obat</label>
                <input type="text" v-model="form.kode" class="form-control" required />
              </div>
              <div class="mb-3">
                <label class="form-label">Nama Obat</label>
                <input type="text" v-model="form.nama" class="form-control" required />
              </div>
              <div class="mb-3">
                <label class="form-label">Satuan</label>
                <input type="text" v-model="form.satuan" class="form-control" required />
              </div>
              <div class="text-end">
                <button type="submit" class="btn btn-success">Simpan</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal backdrop -->
    <div v-if="showModal" class="modal-backdrop fade show"></div>

    <!-- Tombol Kembali -->
    <div class="mt-4 text-start">
      <a href="/farmasi" class="btn btn-secondary">
        <i class=""></i> Kembali
      </a>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { VueGoodTable } from 'vue-good-table-next'
import 'vue-good-table-next/dist/vue-good-table-next.css'

const showModal = ref(false)

const columns = [
  { label: 'KODE', field: 'kode' },
  { label: 'NAMA', field: 'nama' },
  { label: 'SATUAN', field: 'satuan' }
]

const obatList = ref([
  { kode: '11120101000200089', nama: 'Reagen KHB HIV -', satuan: 'test' },
  { kode: '11120101000200090', nama: 'Reagen HIV Combo -', satuan: 'TES' }
])

const form = ref({
  kode: '',
  nama: '',
  satuan: ''
})

function tambahObat() {
  obatList.value.push({ ...form.value })
  form.value = { kode: '', nama: '', satuan: '' }
  showModal.value = false
}
</script>
