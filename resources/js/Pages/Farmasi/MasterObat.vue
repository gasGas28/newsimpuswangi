<script setup>
import AppLayout from '@/Components/Layouts/AppLayouts.vue'
import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'

defineOptions({ layout: AppLayout })

const data = ref([
  { kode: '11120101000200089', nama: 'Reagen KHB HIV -', satuan: 'test' },
  { kode: '11120101000200090', nama: 'Reagen HIV Combo -', satuan: 'TES' },
  { kode: '11120101000200101', nama: 'Kertas saring biasa - -', satuan: 'LEMBAR' },
  { kode: '11120101000200129', nama: 'Desinfektan - Liquid', satuan: 'BOTOL' },
  { kode: '11120101000200158', nama: 'Xilol - -', satuan: 'BOTOL' },
  { kode: '11120101000200264', nama: 'Erlenmeyer 100ml - -', satuan: 'BUAH' },
  { kode: '11120101000200268', nama: 'Gelas ukur 500 ml - -', satuan: 'pcs' },
  { kode: '11120101000200284', nama: 'alat (got) ifccmod -', satuan: 'KIT' },
  { kode: '11120101000200283', nama: 'alat (gpt) fs (fcc mod)', satuan: 'KIT' },
  { kode: '11120101000200318', nama: 'Antiseptik Hand Rub 5L -', satuan: 'BOTOL' },
])
</script>

<template>
  <div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
          <h5 class="mb-0"><i class="bi bi-tags"></i> Master Obat</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
              <thead class="table-light">
                <tr>
                  <th>KODE</th>
                  <th>NAMA</th>
                  <th>SATUAN</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in data" :key="item.kode">
                  <td>{{ item.kode }}</td>
                  <td>{{ item.nama }}</td>
                  <td>{{ item.satuan }}</td>
                </tr>
              </tbody>
            </table>
            <p class="text-muted mt-2">Showing 1 to {{ data.length }} of 1,112 entries</p>
          </div>
          <Link href="/farmasi" class="btn btn-secondary mt-3">
            <i class="bi bi-arrow-left-circle me-2"></i> Kembali
          </Link>
        </div>
      </div>
    </div>
</template>
