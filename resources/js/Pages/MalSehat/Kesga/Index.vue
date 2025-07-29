<script setup>
import AppLayout from '@/Components/Layouts/AppLayouts.vue'
import { router } from '@inertiajs/vue3'

defineOptions({ layout: AppLayout })

const layananKesga = [
  { nama: 'Konseling Catin', file: 'KonselingCatin' },
  { nama: 'Konseling & Pelayanan Calon Jamaah Haji', file: 'KonselingHaji' },
  { nama: 'Konseling & Pelayanan Imunisasi', file: 'KonselingImunisasi' },
  { nama: 'Konseling Kesehatan Bayi & Anak', file: 'KonselingAnak' },
  { nama: 'Konseling Kesehatan Ibu', file: 'KonselingIbu' },
  { nama: 'Konseling Kesehatan KB', file: 'KonselingKB' },
  { nama: 'Konsultasi Gizi & Pencegahan Stunting', file: 'KonsultasiGizi' },
  { nama: 'Konsultasi Kesehatan Lansia', file: 'KonsultasiLansia' },
]

function navigateToLayanan(file) {
  router.get(`/mal-sehat/kesga/${file.toLowerCase()}`)
}

function kembali() {
  router.get('/mal-sehat')
}

function getIcon(nama) {
  if (nama.includes('Catin')) {
    return 'https://img.icons8.com/ios-filled/50/000000/love.png'
  } else if (nama.includes('Haji')) {
    return 'https://img.icons8.com/ios-filled/50/000000/kaaba.png'
  } else if (nama.includes('Imunisasi')) {
    return 'https://img.icons8.com/ios-filled/50/000000/syringe.png'
  } else if (nama.includes('Bayi') || nama.includes('Anak')) {
    return 'https://img.icons8.com/ios-filled/50/000000/baby.png'
  } else if (nama.includes('Ibu')) {
    return 'https://img.icons8.com/ios-filled/50/000000/pregnant.png'
  } else if (nama.includes('KB')) {
    return 'https://img.icons8.com/ios-filled/50/000000/family.png'
  } else if (nama.includes('Gizi')) {
    return 'https://img.icons8.com/ios-filled/50/000000/salad.png'
  } else if (nama.includes('Lansia')) {
    return 'https://img.icons8.com/ios-filled/50/000000/elderly-person.png'
  }
  return 'https://img.icons8.com/ios-filled/50/000000/info.png'
}
</script>

<template>
  <div class="card shadow-sm border-0">
    <div class="card-header bg-white text-dark fw-bold fs-5 border-bottom d-flex justify-content-between align-items-center">
      <span>KESGA - Layanan Kesehatan Keluarga</span>
      <button class="btn btn-sm btn-outline-secondary" @click="kembali">← Kembali</button>
    </div>

    <div class="card-body bg-light-subtle p-3">
      <div class="row g-3">
        <div
          v-for="item in layananKesga"
          :key="item.file"
          class="col-12 col-sm-6 col-lg-4"
        >
          <div
            class="card h-100 border-0 shadow-sm hover-shadow bg-white rounded-0 cursor-pointer"
            @click="navigateToLayanan(item.file)"
          >
            <div class="card-body d-flex justify-content-between align-items-center p-2">
              <div class="text-dark">
                <h6 class="mb-1">{{ item.nama }}</h6>
                <small>Belum ada data</small>
              </div>
              <img
                :src="getIcon(item.nama)"
                alt="icon"
                class="opacity-50"
                style="width: 30px; height: 30px;"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.hover-shadow:hover {
  box-shadow: 0 0.4rem 0.9rem rgba(0, 0, 0, 0.04);
}
</style>
