<script setup>
import AppLayout from '@/Components/Layouts/AppLayouts.vue'
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

defineOptions({ layout: AppLayout })

const layananKesga = ref([
  { nama: 'Konseling Catin', file: 'KonselingCatin', jumlah: 0 },
  { nama: 'Konseling dan Pelayanan Calon Jamaah Haji', file: 'KonselingHaji', jumlah: 0 },
  { nama: 'Konseling dan Pelayanan Imunisasi', file: 'KonselingImunisasi', jumlah: 0 },
  { nama: 'Konseling Kesehatan Bayi dan Anak', file: 'KonselingAnak', jumlah: 0 },
  { nama: 'Konseling Kesehatan Ibu', file: 'KonselingIbu', jumlah: 0 },
  { nama: 'Konseling Kesehatan KB', file: 'KonselingKB', jumlah: 0 },
  { nama: 'Konsultasi Gizi dan Pencegahan Stunting', file: 'KonsultasiGizi', jumlah: 0 },
  { nama: 'Konsultasi Kesehatan Lansia', file: 'KonsultasiLansia', jumlah: 0 },
])

function navigateToLayanan(file) {
  router.get(`/mal-sehat/kesga/${file.toLowerCase()}`)
}

function kembali() {
  router.get('/mal-sehat')
}

function getIcon(nama) {
  if (nama.includes('Catin')) {
    return 'https://img.icons8.com/ios-filled/50/000000/love-message.png'
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
  <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
    <div class="card-header bg-white text-dark fw-bold fs-5 border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
      <span>Layanan - Kesehatan Keluarga</span>
      <button class="btn btn-sm btn-outline-secondary" @click="kembali">← Kembali</button>
    </div>

    <div class="card-body py-4 px-4" style="background-color: #f8fbfd;">
      <div class="row g-4">
        <div
          v-for="item in layananKesga"
          :key="item.file"
          class="col-12 col-sm-6 col-lg-4"
        >
          <div
            class="card h-100 border-0 shadow-sm rounded-4 cursor-pointer"
            style="background-color: #B0E0E6; transition: transform 0.2s ease;"
            @click="navigateToLayanan(item.file)"
            @mouseover="$event.currentTarget.style.transform = 'scale(1.01)'"
            @mouseleave="$event.currentTarget.style.transform = 'scale(1)'"
          >
            <div class="card-body d-flex justify-content-between align-items-center px-3 py-3">
              <div>
                <div class="fw-semibold fs-6 mb-1 text-dark">{{ item.nama }}</div>
                <small class="text-muted">Hari ini {{ item.jumlah }} pasien</small>
              </div>
              <img
                :src="getIcon(item.nama)"
                alt="icon"
                class="opacity-50"
                style="width: 32px; height: 32px;"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
