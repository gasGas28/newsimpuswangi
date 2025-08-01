<script setup>
import AppLayout from '@/Components/Layouts/AppLayouts.vue'
import { ref } from 'vue'

defineOptions({ layout: AppLayout })

const unitOptions = ['PUSKESMAS', 'PUSTU', 'POLINDES', 'POSKESDES', 'PUSLING', 'POSKESTREN', 'PONKESDES']
const keperluanOptions = ['KLB', 'BENCANA', 'PPGD', 'GIZI', 'CITO', 'UGD', 'POSYANDU', 'RANAP', 'TBC']

const unit = ref(unitOptions[0])
const subUnit = ref('PUSKESMAS WONGSOREJO')
const periode = ref(new Date().toISOString().split('T')[0])
const keperluan = ref(keperluanOptions[0])
const resepLangsung = ref([]) // Data dummy untuk tabel
</script>

<template>

    <div class="container py-4">
      <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
          <h5 class="mb-0">Pengeluaran langsung</h5>
        </div>
        <div class="card-body">
          <div class="card border mb-4">
            <div class="card-header bg-light fw-bold">Filter Data</div>
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label fw-bold">Unit</label>
                  <select class="form-select" v-model="unit">
                    <option v-for="option in unitOptions" :key="option" :value="option">{{ option }}</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold">Sub Unit</label>
                  <select class="form-select" v-model="subUnit">
                    <option>PUSKESMAS WONGSOREJO</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold">Periode</label>
                  <input type="date" class="form-control" v-model="periode" />
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-bold">Keperluan</label>
                  <select class="form-select" v-model="keperluan">
                    <option v-for="option in keperluanOptions" :key="option" :value="option">{{ option }}</option>
                  </select>
                </div>
              </div>
              <div class="mt-4 d-flex gap-2">
                <button class="btn btn-success">
                  <i class="bi bi-search me-1"></i> Tampilkan Data
                </button>
                <button class="btn btn-info text-white">
                  <i class="bi bi-save me-1"></i> Simpan Data
                </button>
              </div>
            </div>
          </div>

          <!-- Tabel Data -->
          <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped align-middle">
              <thead class="table-light">
                <tr>
                  <th>No</th>
                  <th>Tanggal</th>
                  <th>Unit</th>
                  <th>Keperluan</th>
                  <th>Jumlah Obat</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="resepLangsung.length === 0">
                  <td colspan="6" class="text-center">Belum ada data</td>
                </tr>
                <tr v-for="(item, index) in resepLangsung" :key="index">
                  <td>{{ index + 1 }}</td>
                  <td>{{ item.tanggal }}</td>
                  <td>{{ item.unit }}</td>
                  <td>{{ item.keperluan }}</td>
                  <td>{{ item.jumlahObat }}</td>
                  <td>
                    <button class="btn btn-sm btn-outline-primary">Detail</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
</template>
