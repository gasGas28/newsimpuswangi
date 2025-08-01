<script setup>
import AppLayout from '@/Components/Layouts/AppLayouts.vue'
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'

const form = ref({
  unit: '',
  subUnit: '',
  periode: new Date().toISOString().split('T')[0],
  keperluan: '',
})

const unitOptions = [
  'PUSKESMAS', 'PUSTU', 'POLINDES', 'POSKESDES',
  'PUSLING', 'POSKESTREN', 'PONKESDES'
]

const keperluanOptions = [
  'KLB', 'BENCANA', 'PPGD', 'GIZI',
  'CITO', 'UGD', 'POSYANDU', 'RANAP', 'TBC'
]

const pasienFarmasi = ref([])
const search = ref('')
const currentPage = ref(1)
const perPage = 5

const filteredPasien = computed(() => {
  if (!search.value) return pasienFarmasi.value
  return pasienFarmasi.value.filter(p =>
    Object.values(p).some(val => String(val).toLowerCase().includes(search.value.toLowerCase()))
  )
})

const totalPages = computed(() => Math.ceil(filteredPasien.value.length / perPage))

const paginatedPasien = computed(() => {
  const start = (currentPage.value - 1) * perPage
  const end = start + perPage
  return filteredPasien.value.slice(start, end)
})


const tampilkanData = () => {
  pasienFarmasi.value = [
     {
      id: 1,
      created_at: '2025-07-30',
      no_rm: '123456',
      alamat: 'Desa A',
      kategori: 'BPJS',
      diagnosa: 'ISPA',
      sample: 'Obat A',
      status_resep: 'Selesai'
    }
  ] 
}
</script>

<template>
  <AppLayout title="Pelayanan Resep dari Poli">
    <div class="card shadow-sm">
      <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Pelayanan Resep dari Poli</h5>
        <Link href="/farmasi" class="btn btn-warning btn-sm">Kembali</Link>
      </div>

      <div class="card-body">
        <h5 class="mb-3">Filter Data</h5>
        <form @submit.prevent="tampilkanData">
          <div class="row mb-3">
            <div class="col-md-4">
              <label class="form-label fw-bold">Unit</label>
              <select v-model="form.unit" class="form-select" required>
                <option value="">-- Pilih Unit --</option>
                <option v-for="option in unitOptions" :key="option" :value="option">{{ option }}</option>
              </select>
            </div>

            <div class="col-md-4">
              <label class="form-label fw-bold">Sub Unit</label>
              <select v-model="form.subUnit" class="form-select" required>
                <option value="">-- Pilih Sub Unit --</option>
                <option value="PUSKESMAS WONGSOREJO">PUSKESMAS WONGSOREJO</option>
                <!-- Tambahkan sesuai kebutuhan -->
              </select>
            </div>

            <div class="col-md-4">
              <label class="form-label fw-bold">Periode</label>
              <input v-model="form.periode" type="date" class="form-control" required />
            </div>
          </div>

          <div class="row mb-4">
            <div class="col-md-4">
              <label class="form-label fw-bold">Keperluan</label>
              <select v-model="form.keperluan" class="form-select" required>
                <option value="">-- Pilih Keperluan --</option>
                <option v-for="option in keperluanOptions" :key="option" :value="option">{{ option }}</option>
              </select>
            </div>
          </div>

          <button type="submit" class="btn btn-success">Tampilkan Data</button>
        </form>

        <hr />

        <div class="d-flex justify-content-between align-items-center">
          <h5 class="mt-3 mb-0">Daftar Pasien Farmasi</h5>
          <input
            v-model="search"
            type="text"
            class="form-control w-auto"
            placeholder="Search..."
          />
        </div>

        <div class="table-responsive mt=3">
          <table class="table table-bordered table-striped table-sm">
            <thead>
              <tr>
                <th>No</th>
                <th>Created</th>
                <th>No RM</th>
                <th>Alamat</th>
                <th>Kategori</th>
                <th>Diagnosa</th>
                <th>Sample</th>
                <th>Status Resep</th>
                <th>Action</th>
              </tr>
            </thead>
             <tbody>
              <tr v-if="paginatedPasien.length === 0">
                <td colspan="9" class="text-center">No data available in table</td>
              </tr>
              <tr v-for="(pasien, index) in paginatedPasien" :key="pasien.id">
                <td>{{ (currentPage - 1) * perPage + index + 1 }}</td>
                <td>{{ pasien.created_at }}</td>
                <td>{{ pasien.no_rm }}</td>
                <td>{{ pasien.alamat }}</td>
                <td>{{ pasien.kategori }}</td>
                <td>{{ pasien.diagnosa }}</td>
                <td>{{ pasien.sample }}</td>
                <td>{{ pasien.status_resep }}</td>
                <td><button class="btn btn-sm btn-primary">Detail</button></td>
              </tr>
            </tbody>
          </table>
          
         <div class="d-flex justify-content-between align-items-center mt-2">
            <small class="text-muted">
              Showing {{ (currentPage - 1) * perPage + 1 }} to
              {{ Math.min(currentPage * perPage, filteredPasien.length) }} of
              {{ filteredPasien.length }} entries
            </small>
            <div>
              <button
                class="btn btn-sm btn-outline-secondary me-1"
                :disabled="currentPage === 1"
                @click="currentPage--"
              >
                Previous
              </button>
              <button
                class="btn btn-sm btn-outline-secondary"
                :disabled="currentPage === totalPages || totalPages === 0"
                @click="currentPage++"
              >
                Next
              </button>
            </div>
          </div>
        </div>

        <Link href="/farmasi" class="btn btn-secondary mt-4">
          <i class="bi bi-arrow-left-circle me-2"></i> Kembali
        </Link>
      </div>
    </div>
  </AppLayout>
</template>
